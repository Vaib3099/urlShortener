<p>Hello {{ $user->name }},</p>

<p>You have been invited as an Member. Use the following credentials to log in:</p>

<p><strong>Email:</strong> {{ $user->email }}</p>
<p><strong>Temporary Password:</strong> {{ $tempPassword }}</p>

<p>Login here: <a href="{{ url('/login') }}">{{ url('/login') }}</a></p>

<p>Please change your password after logging in.</p>
