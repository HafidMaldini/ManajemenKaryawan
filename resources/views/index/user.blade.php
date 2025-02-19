<x-app-layout>
    <h1 class="text-2xl font-bold text-gray-700 mb-6">User  Management</h1>

    <div class="mb-4 text-left">
        <button id="addUserBtn"
            class="px-6 py-2 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 focus:ring-2 focus:ring-green-300">
            Tambah Pengguna
        </button>
    </div>

    <!-- Filter Section -->
    <div class="mb-4">
        <label for="filterRole" class="block text-sm font-medium text-gray-700">Filter Role:</label>
        <select id="filterRole"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            <option value="">Semua</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
        </select>
        <label for="filterTeam" class="block text-sm font-medium text-gray-700">Filter Team:</label>
        <select id="filterTeam"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            <option value="">Semua</option>
            @foreach ($teams as $team)
                <option value="{{ $team->id }}">{{ $team->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Tabel Users -->
    <div class="overflow-x-auto bg-blue-50 rounded-lg shadow-md">
        <table id="usersTable" class="min-w-full bg-white rounded-lg divide-y divide-gray-200">
            <thead class="bg-blue-100">
                <tr class="text-gray-600 uppercase text-xs font-medium leading-normal">
                    <th class="py-3 px-6 text-left">Nama</th>
                    <th class="py-3 px-6 text-left">Email</th>
                    <th class="py-3 px-6 text-left">Role</th>
                    <th class="py-3 px-6 text-left">Team</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-lg text-gray-900">{{ $user->name }}</td>
                        <td class="py-3 px-6 text-lg text-gray-900">{{ $user->email }}</td>
                        <td class="py-3 px-6 text-lg text-gray-900">{{ $user->role->name }}</td>
                        <td class="py-3 px-6 text-lg text-gray-900">{{ $user->team->name }}</td>
                        <td class="py-3 px-6 text-center space-x-2">
                            <button
                                onclick="editUser  ({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', {{ $user->role_id }}, {{ $user->team_id }})"
                                class="text-yellow-500 hover:text-yellow-600 text-lg">
                                <i class="fas fa-edit text-xl"></i>
                            </button>
                            <button onclick="confirmDelete({{ $user->id }})" class="text-red-500 hover:text-red-600 text-lg">
                                <i class="fas fa-trash text-xl"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="userModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg shadow-xl w-1/3">
                <div class="p-4 border-b flex justify-between">
                    <h2 id="modalTitle" class="text-lg font-semibold">Tambah Pengguna</h2>
                    <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <div class="p-4">
                    <form id="userForm" action="#" method="POST">
                        @csrf
                        <input type="hidden" id="userId" name="id">
                        <div id="methodField"></div>

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" id="name" name="name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" name="email"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div class="mb-4">
                            <label for="role_id" class="block text-sm font-medium text-gray-700">Role</label>
                            <select id="role_id" name="role_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ $role->name == 'Karyawan' ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="team_id" class="block text-sm font-medium text-gray-700">Team</label>
                            <select id="team_id" name="team_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @foreach ($teams as $team)
                                    <option value="{{ $team->id }}"
                                        {{ $team->name == 'Marketing' ? 'selected' : '' }}>
                                        {{ $team->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="passwordFields" class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" id="password" name="password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

                            <label for="password_confirmation"
                                class="block text-sm font-medium text-gray-700 mt-2">Konfirmasi Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div class="text-right">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let isFetching = false;

        new DataTable('#usersTable');

        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(event) {
                if (this.action == "{{ route('logout') }}") {
                    return;
                }
                event.preventDefault();
                const action = this.action;
                const method = this.method;
                const formData = new FormData(this);

                console.log(action, method, formData)

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
                        .finally(() => {
                            isFetching = false;
                        });
                }
            });
        });

        const modal = document.getElementById('userModal');
        const addUserBtn = document.getElementById('addUserBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const modalTitle = document.getElementById('modalTitle');
        const userForm = document.getElementById('userForm');
        const methodField = document.getElementById('methodField');
        const userIdInput = document.getElementById('userId');
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const roleSelect = document.getElementById('role_id');
        const teamSelect = document.getElementById('team_id');
        const passwordFields = document.getElementById('passwordFields');

        addUserBtn.addEventListener('click', () => {
            modalTitle.innerText = 'Tambah Pengguna';
            userForm.action = '{{ route('user.store') }}';
            userIdInput.value = '';
            nameInput.value = '';
            emailInput.value = '';
            roleSelect.value = '{{ $roles->firstWhere('name', 'Karyawan')->id ?? '' }}';
            teamSelect.value = '{{ $teams->firstWhere('name', 'Marketing')->id ?? '' }}';
            methodField.innerHTML = '';
            passwordFields.style.display = 'block';
            modal.classList.remove('hidden');
        });

        closeModalBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        window.editUser  = (id, name, email, roleId, teamId) => {
            modalTitle.innerText = 'Edit Pengguna';
            userForm.action = `{{ url('UserManagement-edit/') }}/${id}`;
            userIdInput.value = id;
            nameInput.value = name;
            emailInput.value = email;
            roleSelect.value = roleId;
            teamSelect.value = teamId;
            methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            modal.classList.remove('hidden');
        };

        function confirmDelete(userId) {
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
                    deleteUsers(userId);
                }
            });
        }
        
        function deleteUsers(userId) {
            fetch(`{{ url('UserManagement-destroy/') }}/${userId}`, {
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

        function filterTable() {
            let roleId = $('#filterRole').val();
            let teamId = $('#filterTeam').val();
            let table = $('#usersTable').DataTable();
            let role = '';
            let team = '';
            if (roleId == 1) {
                role = 'Karyawan';
            } else if (roleId == 2) {
                role = 'Manager';
            };

            if (teamId == 1) {
                team = 'HRD';
            } else if (teamId == 2) {
                team = 'Developer';
            } else if (teamId == 3) {
                team = 'Marketing'
            }
            console.log(role, team);
            table.columns(2).search(role).column(3).search(team).draw();
        }

        $('#filterRole, #filterTeam').on('change', function() {
            filterTable();
        });
    </script>
</x-app-layout>