<!DOCTYPE html>
<html>
<head>
    <title>Laravel Auth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">MyApp</a>

            <!-- Logout link -->
            <a href="#" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="ms-3 text-white">
                Logout
            </a>

            <!-- Hidden logout form -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>
</body>
</html>
