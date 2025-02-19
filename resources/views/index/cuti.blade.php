<x-app-layout>
    <h1 class="text-2xl font-bold text-gray-700 mb-6">Pengajuan Cuti</h1>

    <!-- Tombol Ajukan Cuti -->
    <div class="mb-4 text-left">
        <button id="addCutiBtn"
            class="px-6 py-2 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 focus:ring-2 focus:ring-green-300">
            Ajukan Cuti
        </button>
    </div>

    <div class="mb-4 p-4 bg-blue-100 rounded-lg">
        <p class="text-lg font-semibold">Sisa Cuti Anda: <span class="text-blue-700">{{ Auth::user()->sisa_cuti }} hari</span></p>
    </div>

    <!-- Filter Status -->
    <div class="mb-4">
        <label for="filterStatus" class="block text-sm font-medium text-gray-700">Filter Status:</label>
        <select id="filterStatus" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            <option value="">Semua</option>
            <option value="pending">pending</option>
            <option value="approved">approved</option>
            <option value="rejected">rejected</option>
        </select>
    </div>

    <!-- Tabel Pengajuan Cuti -->
    <div class="overflow-x-auto bg-blue-50 rounded-lg shadow-md">
        <table id="cutiTable" class="min-w-full bg-white rounded-lg divide-y divide-gray-200">
            <thead class="bg-blue-100">
                <tr class="text-gray-600 uppercase text-xs font-medium leading-normal">
                    <th class="py-3 px-6 text-left">Nama Pegawai</th>
                    <th class="py-3 px-6 text-left">Tanggal</th>
                    <th class="py-3 px-6 text-left">Alasan</th>
                    <th class="py-3 px-6 text-left">Status</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cutis as $item)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-lg text-gray-900">{{ $item->user->name }}</td>
                        <td class="py-3 px-6 text-lg text-gray-900">{{ $item->tanggal }}</td>
                        <td class="py-3 px-6 text-lg text-gray-900">{{ $item->reason }}</td>
                        <td class="py-3 px-6 text-lg text-gray-900">{{ $item->status }}</td>
                        <td class="py-3 px-6 text-center space-x-2">
                            @if ($item->status === 'pending' && Auth::user()->role->name === 'Manager' && Auth::user()->team->name === $item->user->team->name)
                                <button onclick="approve({{ $item->id }})" class="text-green-500 hover:text-green-600 text-lg">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button onclick="reject({{ $item->id }})" class="text-red-500 hover:text-red-600 text-lg">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Pengajuan Cuti -->
    <div id="cutiModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg shadow-xl w-1/3">
                <div class="p-4 border-b flex justify-between">
                    <h2 class="text-lg font-semibold">Ajukan Cuti</h2>
                    <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <div class="p-4">
                    <form id="cutiForm" action="{{ route('cuti.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" id="end_date" name="tanggal" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <div class="mb-4">
                            <label for="reason" class="block text-sm font-medium text-gray-700">Alasan Cuti</label>
                            <textarea id="reason" name="reason" rows="3" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                        </div>
                        <div class="text-right">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 focus:ring-2 focus:ring-blue-300">Ajukan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const yyyy = tomorrow.getFullYear();
    const mm = String(tomorrow.getMonth() + 1).padStart(2, '0');
    const dd = String(tomorrow.getDate()).padStart(2, '0');

    document.getElementById('end_date').min = `${yyyy}-${mm}-${dd}`;
        let isFetching = false;

new DataTable('#cutiTable');

// Notifikasi untuk setiap aksi
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(event) {
        if (this.action == "{{ route('logout') }}") {
            return;
        }
        event.preventDefault();
        const action = this.action;
        const method = this.method;
        const formData = new FormData(this);
        console.log(action, method, formData);

        if (!isFetching) {
            isFetching = true;
            fetch(action, {
                    method: method,
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data)
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message || 'Aksi berhasil dilakukan.',
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message || 'Gagal melakukan aksi.',
                        });
                    }
                })
                .catch(error => {
                    console.log(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat melakukan aksi.',
                    });
                })
                .finally(() => {
                    isFetching = false;
                });
        }
    });
});

        const modal = document.getElementById('cutiModal');
        const addCutiBtn = document.getElementById('addCutiBtn');
        const cutiForm = document.getElementById('cutiForm');
        const closeModalBtn = document.getElementById('closeModalBtn');
 
        addCutiBtn.addEventListener('click', () => {
            cutiForm.action = '{{ route('cuti.store') }}';
            modal.classList.remove('hidden');
        });
        
        closeModalBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        function approve(id) {
            Swal.fire({
                title: 'Yakin menyetujui cuti ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    approveCuti(id);
                }
            });
        }

        function reject(id) {
            Swal.fire({
                title: 'Yakin menolak cuti ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    rejectCuti(id);
                }
            });
        }

        function approveCuti(id) {
            fetch(`{{ url('cuti/approve/') }}/${id}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message || 'Aksi berhasil dilakukan.',
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: data.message || 'Gagal melakukan aksi.',
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan saat melakukan aksi.',
                            });
                        })
        }

        function rejectCuti(id) {
            fetch(`{{ url('cuti/reject/') }}/${id}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message || 'Aksi berhasil dilakukan.',
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: data.message || 'Gagal melakukan aksi.',
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan saat melakukan aksi.',
                            });
                        })
        }

        function filterTable() {
                let statuse = $('#filterStatus').val();
                console.log(status)
                let table = $('#cutiTable').DataTable();

                table.columns(3).search(statuse).draw();
            }

            // Event Listener untuk Filter
            $('#filterStatus').on('change', function() {
                filterTable();
            });
    </script>
</x-app-layout>
