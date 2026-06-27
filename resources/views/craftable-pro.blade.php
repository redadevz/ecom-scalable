<!DOCTYPE html>
<html class="h-full bg-gray-100/50 dark:bg-gray-950/95" lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    <title>{{ ___('craftable-pro', 'Craftable PRO') }}</title>

    @routes

    @vite(['resources/js/craftable-pro/index.ts', 'resources/css/craftable-pro.css'])

    @inertiaHead
</head>

<body class="h-full">
    @inertia
</body>

</html>
