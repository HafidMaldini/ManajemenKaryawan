<x-app-layout>
    {{-- <div class="container mx-auto p-12 bg-white rounded-lg shadow-md"> --}}
    <h1 class="text-2xl font-bold text-gray-700 mb-6">List Task</h1>

    @if(Auth::user()->role->name === 'Manager')
    <div class="mb-4 text-left">
        <button id="addTugasBtn"
            class="px-6 py-2 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 focus:ring-2 focus:ring-green-300">
            Tambah Tugas
        </button>
    </div>
    @endif
    <div class="mb-4">
        <label for="filterStatus" class="block text-sm font-medium text-gray-700">Filter Status:</label>
        <select id="filterStatus"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            <option value="">Semua</option>
            <option value="Assigned">Assigned</option>
            <option value="On Progress">On Progress</option>
            <option value="On Hold">On Hold</option>
            <option value="Revised">Revised</option>
            <option value="Submited">Submited</option>
        </select>
    </div>

    <div class="overflow-x-auto bg-blue-50 rounded-lg shadow-md">
        <table id="tugasTable" class="min-w-full bg-white rounded-lg divide-y divide-gray-200">
            <thead class="bg-blue-100">
                <tr class="text-gray-600 uppercase text-xs font-medium leading-normal">
                    <th class="py-3 px-6 text-left">Manager </th>
                    <th class="py-3 px-6 text-left">Karyawan</th>
                    <th class="py-3 px-6 text-left">Nama Tugas</th>
                    <th class="py-3 px-6 text-left">Status</th>
                    <th class="py-3 px-6 text-left">Priority</th>
                    <th class="py-3 px-6 text-left">Deadline</th>
                    <th class="py-3 px-6 text-left">Completed</th>
                    <th class="py-3 px-6 text-left">Notes</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tugas as $key => $item)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-lg text-gray-900">{{ $item->manager->name }}</td>
                        <td class="py-3 px-6 text-lg text-gray-900">{{ $item->karyawan->name }}</td>
                        <td class="py-3 px-6 text-lg text-gray-900">{{ $item->title }}</td>
                        <td class="py-3 px-6 text-lg text-gray-900">{{ $item->status }}</td>
                        <td class="py-3 px-6 text-lg text-gray-900">{{ $item->priority }}</td>
                        <td class="py-3 px-6 text-lg text-gray-900">{{ $item->deadline }}</td>
                        <td class="py-3 px-6 text-lg text-gray-900">{{ $item->completed_at }}</td>
                        <td class="py-3 px-6 text-lg text-gray-900">{{ $item->notes }}</td>
                        <td class="py-3 px-6 text-center space-x-2">
                            @if ($item->status === 'Assigned' && $item->karyawan_id === Auth::user()->id)
                                <button onclick="start({{ $item->id }})"
                                    class="text-blue-500 hover:text-blue-600 text-lg">
                                    <i class="fas fa-play text-xl"></i>
                                </button>
                            @endif

                            @if ($item->status === 'On Hold' || ($item->status === 'Revised' && $item->karyawan_id === Auth::user()->id))
                                <form action="{{ route('task.resume', $item->id) }}" method="POST"
                                    class="resume-form inline-block">
                                    @csrf
                                    <button type="submit" class="text-blue-500 hover:text-blue-600 text-lg">
                                        <i class="fas fa-arrow-right text-xl"></i>
                                    </button>
                                </form>
                            @endif

                            @if ($item->status === 'On Progress' && $item->karyawan_id === Auth::user()->id)
                                <button onclick="selesai({{ $item->id }})"
                                    class="text-green-500 hover:text-green-600 text-lg">
                                    <i class="fas fa-check text-xl"></i>
                                </button>
                                <form action="{{ route('task.hold', $item->id) }}" method="POST"
                                    class="hold-form inline-block">
                                    @csrf
                                    <button type="submit" class="text-yellow-500 hover:text-yellow-600 text-lg">
                                        <i class="fas fa-pause text-xl"></i>
                                    </button>
                                </form>
                            @endif

                            <!-- Tombol Approve/Reject -->
                            @if ($item->status === 'Submited' && $user->role->name === 'Manager')
                                <button onclick="approve({{ $item->id }})"
                                    class="text-green-500 hover:text-green-600 text-lg">
                                    <i class="fas fa-thumbs-up text-xl"></i>
                                </button>
                                <button onclick="reject({{ $item->id }})"
                                    class="text-red-500 hover:text-red-600 text-lg">
                                    <i class="fas fa-thumbs-down text-xl"></i>
                                </button>
                            @endif

                            @if ($item->manager_id == $user->id)
                                <button
                                    onclick="editTugas({{ $item->id }}, '{{ $item->title }}', '{{ $item->priority }}', '{{ $item->karyawan_id }}', '{{ $item->deadline }}')"
                                    class="text-yellow-500 hover:text-yellow-600 text-lg">
                                    <i class="fas fa-edit text-xl"></i>
                                </button>

                                <button onclick="confirmDelete({{ $item->id }})"
                                    class="text-red-500 hover:text-red-600 text-lg">
                                    <i class="fas fa-trash text-xl"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>

    <div id="tugasModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg shadow-xl w-1/3">
                <div class="p-4 border-b flex justify-between">
                    <h2 id="modalTitle" class="text-lg font-semibold">Tambah Tugas</h2>
                    <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <div class="p-4">
                    <form id="tugasForm" action="#" method="POST">
                        @csrf
                        <input type="hidden" id="tugasId" name="id">
                        <div id="methodField"></div> <!-- Placeholder untuk method PUT -->
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Nama Tugas</label>
                            <input type="text" id="title" name="title"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                        <label for="deadline" class="block text-sm font-medium text-gray-700">Deadline</label>
                        <input type="date" id="deadline" name="deadline" value="" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <div class="mb-4">
                        </div>
                        <div class="mb-4">
                            <label for="karyawan_id" class="block text-sm font-medium text-gray-700">Karyawan</label>
                            <select id="karyawan_id" name="karyawan_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @foreach ($users as $x)
                                    <option value="{{ $x->id }}"
                                        {{ $x->id == $users[0]->id ? 'selected' : '' }}>
                                        {{ $x->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                            <select id="priority" name="priority"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @foreach (['Easy', 'Medium', 'Hard'] as $x)
                                    <option value="{{ $x }}" {{ $x == 'Hard' ? 'selected' : '' }}>
                                        {{ $x }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-right">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 focus:ring-2 focus:ring-blue-300">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <div id="notesModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white rounded-lg shadow-xl w-1/3">
                    <div class="p-4 border-b flex justify-between">
                        <h2 class="text-lg font-semibold">Tambahkan notes</h2>
                        <button id="closeModalBtn2" class="text-gray-400 hover:text-gray-600">&times;</button>
                    </div>
                    <div class="p-4">
                        <form id="notesForm" action="#" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea id="notes" name="notes" rows="3" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                            </div>
                            <div class="text-right">
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 focus:ring-2 focus:ring-blue-300">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- </div> --}}

        <script>
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const yyyy = tomorrow.getFullYear();
            const mm = String(tomorrow.getMonth() + 1).padStart(2, '0');
            const dd = String(tomorrow.getDate()).padStart(2, '0');

            document.getElementById('deadline').min = `${yyyy}-${mm}-${dd}`;
            let isFetching = false;

            new DataTable('#tugasTable');

            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(event) {
                    if (this.action == "{{ route('logout') }}") {
                        return;
                    }
                    event.preventDefault();
                    const action = this.action;
                    const method = this.method;
                    const formData = new FormData(this);

                    if (!isFetching) {
                        isFetching = true;
                        fetch(action, {
                                method: method,
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content'),
                                    'Accept': 'application/json',
                                }
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
                                console.error();
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

            const modal = document.getElementById('tugasModal');
            const modal2 = document.getElementById('notesModal');
            const addTugasBtn = document.getElementById('addTugasBtn');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const closeModalBtn2 = document.getElementById('closeModalBtn2');
            const modalTitle = document.getElementById('modalTitle');
            const tugasForm = document.getElementById('tugasForm');
            const notesForm = document.getElementById('notesForm');
            const methodField = document.getElementById('methodField');
            const tugasIdInput = document.getElementById('tugasId');
            const namaInput = document.getElementById('title');
            const prioritySelect = document.getElementById('priority');
            const karyawanSelect = document.getElementById('karyawan_id')
            const deadlineDate = document.getElementById('deadline')

            addTugasBtn.addEventListener('click', () => {
                modalTitle.innerText = 'Tambah Tugas';
                tugasForm.action = '{{ route('task.store') }}';
                tugasIdInput.value = '';
                namaInput.value = '';
                prioritySelect.value = '{{ $x }}';
                methodField.innerHTML = '';
                modal.classList.remove('hidden');
            });

            closeModalBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
            closeModalBtn2.addEventListener('click', () => {
                modal.classList.add('hidden');
            });

            // window.selesai = (id) => {
            //     console.log(deadline)
            //     notesForm.action = `{{ url('task-submit') }}/${id}`;
            //     modal2.classList.remove('hidden');
            // };

            window.editTugas = (id, title, priority, karyawan_id, deadline) => {
                console.log(deadline)
                modalTitle.innerText = 'Edit Tugas';
                tugasForm.action = `{{ url('task-edit') }}/${id}`;
                tugasIdInput.value = id;
                namaInput.value = title;
                prioritySelect.value = priority;
                karyawanSelect.value = karyawan_id;
                deadlineDate.value = deadline;
                console.log(karyawan_id);
                methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                modal.classList.remove('hidden');
            };

            function selesai(id) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda Akan Menandai Tugas Ini Sebagai Selesai!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Selesai!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        modal2.classList.remove('hidden');
                        notesForm.action = `{{ url('task-submit') }}/${id}`;
                    }
                });
            }

            function start(id) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda Akan Memulai Tugas Ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Mulai!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        startTask(id);
                    }
                });
            }

            function approve(id) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda Akan Menyetujui Tugas Ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Approve!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        approveTask(id);
                    }
                });
            }

            function reject(id) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda Akan Menolak Tugas Ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Tolak!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        rejectTask(id);
                    }
                });
            }

            function confirmDelete(id) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda tidak dapat mengembalikan data yang dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteTask(id);
                    }
                });
            }

            function startTask(id) {
                fetch(`{{ url('task-start/') }}/${id}`, {
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

            // function finishTask(id) {
            //     fetch(`{{ url('task-submit/') }}/${id}`, {
            //             method: "POST",
            //             headers: {
            //                 "X-CSRF-TOKEN": "{{ csrf_token() }}",
            //                 "Content-Type": "application/json"
            //             },
            //             body: JSON.stringify({})
            //         })
            //         .then(response => response.json())
            //         .then(data => {
            //             if (data.success) {
            //                 Swal.fire({
            //                     icon: 'success',
            //                     title: 'Berhasil!',
            //                     text: data.message || 'Aksi berhasil dilakukan.',
            //                 }).then(() => {
            //                     window.location.reload();
            //                 });
            //             } else {
            //                 Swal.fire({
            //                     icon: 'error',
            //                     title: 'Gagal!',
            //                     text: data.message || 'Gagal melakukan aksi.',
            //                 });
            //             }
            //         })
            //         .catch(error => {
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'Oops...',
            //                 text: 'Terjadi kesalahan saat melakukan aksi.',
            //             });
            //         })
            // }

            function approveTask(id) {
                fetch(`{{ url('task-approve/') }}/${id}`, {
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

            function rejectTask(id) {
                fetch(`{{ url('task-reject/') }}/${id}`, {
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

            function deleteTask(id) {
                fetch(`{{ url('task-destroy/') }}/${id}`, {
                        method: "DELETE",
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

            document.getElementById('filterStatus').addEventListener('change', function() {
                const filterValue = this.value.toLowerCase();
                const rows = document.querySelectorAll('#tugasTable tbody tr');

                rows.forEach(row => {
                    const statusCell = row.querySelector('td:nth-child(6)').textContent.toLowerCase();
                    if (filterValue === '' || statusCell.includes(filterValue)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        </script>
</x-app-layout>
