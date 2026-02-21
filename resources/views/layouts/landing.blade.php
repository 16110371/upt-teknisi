<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'UPT - SMK Syubbanul Wathon')</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}" />

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: "Inter", sans-serif;
        }

        .bg-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-pattern text-slate-800">

    @yield('content')

    @stack('scripts')

</body>

</html>
