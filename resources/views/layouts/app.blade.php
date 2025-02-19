<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">  
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet" />
        <script src="{{ asset('JS/jquery.js') }}"></script>
        {{-- <script src="{{ asset('JS/jquery2.js') }}"></script> --}}
        <script src="{{ asset('JS/datatable.js') }}"></script>
        <script src="{{ asset('JS/swal.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('css/datatable.css') }}">

        {{-- <script src="cdn.datatables.net/2.2.1/js/dataTables.min.js"></script> --}}
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased flex gap-4">
        <x-sidebar/>
        <main class="grow p-4">
            {{ $slot }}
        </main>
    </body>
</html>
