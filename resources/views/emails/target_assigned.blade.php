<!DOCTYPE html>
<html>
<head>
    <title>Target Assigned</title>
</head>
<body>

    <h2>Hello, {{ $employee->first_name ?? 'Employee' }}</h2>

    <p>You have been assigned a new monthly target.</p>

    <p><b>Month:</b> {{ $month }}</p>
    <p><b>Target Value:</b> {{ $targetValue }}</p>

    <p>Please complete your tasks accordingly.</p>

</body>
</html>
