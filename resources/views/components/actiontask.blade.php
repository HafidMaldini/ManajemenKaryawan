@if($task->status === 'Assigned')
<form action="{{ route('task.start', $task->id) }}" method="POST">
    @csrf
    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600">
        Mulai
    </button>
</form>
@endif

@if($task->status === 'On Progress')
<form action="{{ route('task.submit', $task->id) }}" method="POST">
    @csrf
    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600">
        Selesai
    </button>
</form>
@endif

@if($task->status === 'Submited' && auth()->user()->role === 'manager')
<form action="{{ route('task.approve', $task->id) }}" method="POST">
    @csrf
    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600">
        Approve
    </button>
</form>
<form action="{{ route('task.reject', $task->id) }}" method="POST">
    @csrf
    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg shadow-md hover:bg-red-600">
        Reject
    </button>
</form>
@endif

@if($task->user_id == auth()->id())
<button onclick="editTugas({{ $task->id }}, '{{ $task->judul }}', '{{ $task->deskripsi }}')" class="px-4 py-2 bg-yellow-500 text-white rounded-lg shadow-md hover:bg-yellow-600">
    Edit
</button>
<form action="{{ route('task.destroy', $task->id) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg shadow-md hover:bg-red-600">
        Hapus
    </button>
</form>
@endif

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(event) {
                if (this.action == "{{ route('logout') }}") {
                    return;
                }
                event.preventDefault();
                const action = this.action;
                const method = this.method;
                console.log(action);
                console.log(method);
                const formData = new FormData(this);


                if (!isFetching) {
                    isFetching = true
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

        // Modal untuk Tambah/Edit Tugas
        const modal = document.getElementById('tugasModal');
        const addTugasBtn = document.getElementById('addTugasBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const modalTitle = document.getElementById('modalTitle');
        const tugasForm = document.getElementById('tugasForm');
        const methodField = document.getElementById('methodField');
        const tugasIdInput = document.getElementById('tugasId');
        const namaInput = document.getElementById('judul');
        const deskripsiInput = document.getElementById('deskripsi');

        addTugasBtn.addEventListener('click', () => {
            modalTitle.innerText = 'Tambah Tugas';
            tugasForm.action = '{{ route('task.store') }}';
            tugasIdInput.value = '';
            namaInput.value = '';
            deskripsiInput.value = '';
            methodField.innerHTML = ''; // Hapus PUT method untuk tambah
            modal.classList.remove('hidden');
        });

        closeModalBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        window.editTugas = (id, judul, deskripsi) => {
            modalTitle.innerText = 'Edit Tugas';
            tugasForm.action = `{{ url('task-edit') }}/${id}`;
            tugasIdInput.value = id;
            namaInput.value = judul;
            deskripsiInput.value = deskripsi;
            methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            modal.classList.remove('hidden');
        };

</script>