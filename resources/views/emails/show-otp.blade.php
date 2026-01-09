<!DOCTYPE html>
<html>

<head>
    <title>Your OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="text-center p-5 bg-white rounded shadow" style="max-width: 400px; width: 100%;">
        <h2 class="mb-3">Your OTP Code</h2>

        @if (isset($otp))
            <div class="display-4 fw-bold text-primary">{{ $otp }}</div>
            <p class="mt-3 text-muted">Use this code to verify your email.</p>
        @else
            <p class="text-danger">No OTP found.</p>
        @endif
    </div>
</body>

</html>
