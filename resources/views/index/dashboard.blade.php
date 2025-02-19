<x-app-layout>
    {{-- <div class="w-screen h-screen flex items-center justify-center"> --}}
        <div class="bg-white p-8 rounded-lg shadow-lg w-full text-center">
            <h1 class="text-3xl font-bold mb-4">Absensi Karyawan</h1>

            <!-- Waktu Sekarang -->
            <p class="text-2xl font-semibold text-blue-600 mb-4" id="current-time">
                {{ now()->setTimezone('Asia/Jakarta')->format('H:i:s') }}
            </p>

            {{-- @if(session('success'))
                <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif --}}

            {{-- @if(session('error'))
                <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif --}}

            <button onclick="absenMasuk()" class="w-full bg-blue-500 text-white py-3 max-w-4xl rounded-lg hover:bg-blue-600 transition text-lg">
                Absen Masuk
            </button>

            <button onclick="checkout()" class="w-full bg-red-500 text-white py-3 max-w-4xl rounded-lg hover:bg-red-600 transition text-lg mt-4">
                Checkout Pulang
            </button>

            <div class="mt-6">
                <h2 class="text-lg font-semibold mb-2">Jadwal Kalender</h2>
                <div class="grid grid-cols-5 gap-2 text-center">
                    @php
                        $startDate = now()->subDays(4);
                        $endDate = now()->addDays(10);
                    @endphp
                    @for ($date = $startDate; $date->lte($endDate); $date->addDay())
                        <button onclick="showAbsensi('{{ $date->format('Y-m-d') }}')"
                            class="p-3 rounded-lg {{ $date->isToday() ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
                            {{ $date->format('d M') }}
                        </button>
                    @endfor
                </div>
            </div>

            <!-- Info Absen -->
            <div id="absen-info" class="mt-4 text-lg bg-gray-200 p-3 rounded"></div>
        </div>
    {{-- </div> --}}

    <script>
        function updateTime() {
            document.getElementById('current-time').innerText = new Date().toLocaleTimeString('id-ID', {
                timeZone: "Asia/Jakarta",
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit"
            });
        }
        setInterval(updateTime, 1000);

        function showAbsensi(date) {
            fetch(`/absensi/${date}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('absen-info').innerHTML = data 
                        ? `<p><strong>${date}</strong></p><p>Masuk: ${data.jam_masuk || 'Belum Checkin'} </p><p>Pulang: ${data.jam_pulang || 'Belum Checkout'}</p>`
                        : "<p>Data tidak ditemukan</p>";
                });
        }

        function absenMasuk() {
            fetch("{{ route('absen') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    title: data.status === 'success' ? 'Sukses!' : 'Gagal!',
                    text: data.message,
                    icon: data.status
                });
            });
        }

        function checkout() {
            fetch("{{ route('checkout') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    title: data.status === 'success' ? 'Sukses!' : 'Gagal!',
                    text: data.message,
                    icon: data.status
                });
            });
        }
    </script>
</x-app-layout>
