<?php

namespace App\Services;

use App\Mail\InvitationMail;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InvitationService
{
    public function sendInvitation(array $data, $user)
    {
        return DB::transaction(function () use ($data, $user) {

            $invitation = Invitation::create([
                'uuid' => Str::uuid(),

                'company_id' => $user->company_id,

                'role_id' => $data['role_id'],

                'first_name' => $data['first_name'],

                'last_name' => $data['last_name'] ?? null,

                'email' => strtolower($data['email']),

                'token' => Str::random(64),

                'expires_at' => now()->addDays(7),

                'created_by' => $user->id,
            ]);

            $invitation->load([
                'company',
                'role',
                'creator',
            ]);

            Log::info('================ Invitation Email ================');

            Log::info('To Email', [
                'email' => $invitation->email,
            ]);

            Log::info('Invitation Data', [
                'uuid' => $invitation->uuid,
                'token' => $invitation->token,
                'company' => $invitation->company->company_name,
                'role' => $invitation->role->name,
            ]);

            // Mail::to($invitation->email)
            //     ->send(new InvitationMail($invitation));

            Log::info('Invitation email skipped (SMTP disabled for development).');

            Log::info('==================================================');

            return $invitation;
        });
    }

    public function acceptInvitation(array $data)
    {
        $invitation = Invitation::with([
            'role',
            'company',
        ])
            ->where('token', $data['token'])
            ->first();

        if (! $invitation) {
            throw ValidationException::withMessages([
                'token' => ['Invalid invitation token.'],
            ]);
        }

        if ($invitation->accepted_at) {
            throw ValidationException::withMessages([
                'token' => ['Invitation already accepted.'],
            ]);
        }

        if ($invitation->expires_at->isPast()) {
            throw ValidationException::withMessages([
                'token' => ['Invitation has expired.'],
            ]);
        }

        return DB::transaction(function () use ($invitation, $data) {

            $user = User::create([
                'uuid' => Str::uuid(),
                'company_id' => $invitation->company_id,
                'first_name' => $invitation->first_name,
                'last_name' => $invitation->last_name,
                'email' => $invitation->email,
                'password' => Hash::make($data['password']),
                'is_owner' => false,
                'status' => true,
            ]);

            $user->assignRole($invitation->role);

            $invitation->update([
                'accepted_at' => now(),
            ]);

            $token = $user->createToken('broker-api')->plainTextToken;

            return [
                'token' => $token,
                'user' => $user->load('company', 'roles'),
            ];
        });
    }
}
