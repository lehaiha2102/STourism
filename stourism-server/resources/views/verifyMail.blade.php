<!DOCTYPE html>
<html>
<head>
    <title>Confirm email</title>
    <style>
        body {
            text-align: center;
        }
    </style>
</head>
<body>
<h1>Verify your email</h1>
<p>Make sure your account is secure by verifying your email.</p>
<a type="button" href="{{route('verify', ['email' => $email, 'active_key' => $active_key])}}" style="background-color:red; border:none; border-radius:10px;text-decoration: none; color:black;">Email Verification</a>
</body>
</html>
