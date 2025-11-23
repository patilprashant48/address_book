<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Address Book</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<header class="topbar">
    <h1>Address Book</h1>
</header>
<main class="container">
    @yield('content')
</main>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>