<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invitation</title>
</head>
<body>

    <h2>Hello {{ $invitation->first_name }},</h2>

    <p>
        You have been invited to join
        <strong>{{ $invitation->company->company_name }}</strong>.
    </p>

    <p>
        Your assigned role is:
        <strong>{{ $invitation->role->name }}</strong>
    </p>

    <p>
        Click the button below to accept your invitation.
    </p>

    <p>
        <a href="{{ env('FRONTEND_URL') }}/accept-invitation/{{ $invitation->token }}"
           style="
                background:#2563eb;
                color:white;
                padding:12px 20px;
                text-decoration:none;
                border-radius:6px;
           ">
            Accept Invitation
        </a>
    </p>

    <p>
        This invitation expires on
        {{ $invitation->expires_at->format('d M Y h:i A') }}.
    </p>

</body>
</html>