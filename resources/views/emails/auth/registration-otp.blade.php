<x-mail::message>
# Registration OTP

Your One-Time Password for registration is: **{{ $otp }}**

This OTP is valid for 10 minutes.

If you did not request this, please ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
