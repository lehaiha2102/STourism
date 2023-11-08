<!-- resources/views/emails/mail.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Email Confirmation</title>
</head>
<body>
<p>Hello {{ $user['full_name'] }},</p>

<p>Thank you for signing up with S Tourism.</p>
<p>This is your authentication code, please do not share this code with anyone.</p>
<strong>{{ $user['token'] }}</strong>
<p>If you didn't sign up for S Tourism, please ignore this email.</p>
</body>
</html>
