<h1>Email Verification</h1>

Dear {!! $name !!}

<p>Click on the link below to verify your email address.</p>

{{ url('/email/verify/' . $verification_code) }}

