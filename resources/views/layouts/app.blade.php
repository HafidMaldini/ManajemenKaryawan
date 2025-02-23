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
        <script src="{{ asset('JS/jquery.js') }}"></script>
        {{-- <script src="{{ asset('JS/jquery2.js') }}"></script> --}}
        <script src="{{ asset('JS/datatable.js') }}"></script>
        <script src="{{ asset('JS/swal.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('css/datatable.css') }}">
        {{-- <link rel="shortcut icon" href="" type="image/x-icon"> --}}

        {{-- <script src="cdn.datatables.net/2.2.1/js/dataTables.min.js"></script> --}}
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased flex gap-4">
        <x-sidebar/>
        <main class="grow p-4">
            <div id="notifContainer" class="fixed top-4 right-4 w-80 space-y-3 z-50">
                @foreach(auth()->user()->unreadNotifications as $notification)
                    <div class="bg-white shadow-lg rounded-lg p-4 border-l-4 border-blue-500 flex flex-col items-center text-center notif-item animate-fadeIn" data-id="{{ $notification->id }}">
                        <p class="text-sm text-gray-700">{{ $notification->data['message'] }}</p>
                        <button class="mt-3 bg-blue-500 text-white text-xs font-bold px-4 py-2 rounded hover:bg-blue-600 transition notif-ok">OK</button>
                    </div>
                @endforeach
            </div>
    
            <!-- Script untuk menghapus notifikasi -->
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    document.querySelectorAll('.notif-ok').forEach(button => {
                        button.addEventListener('click', function () {
                            let notifDiv = this.closest('.notif-item');
                            let notifId = notifDiv.getAttribute('data-id');
    
                            fetch(`/notifications/delete/${notifId}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                            }).then(response => {
                                if (response.ok) {
                                    notifDiv.classList.add('animate-fadeOut');
                                    setTimeout(() => notifDiv.remove(), 300);
                                }
                            });
                        });
                    });
                });
            </script> 
            {{ $slot }}
        </main>
    </body>
</html>
