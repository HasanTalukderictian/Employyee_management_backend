<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>User Created</title>
</head>
<body>
    <h2>Welcome, {{ $user->name ?? 'User' }}!</h2>
    <p>Your account has been successfully created.</p>
    <p>Email: {{ $user->email }}</p>
    <p>Thank you for joining us.</p>
</body>
</html>
