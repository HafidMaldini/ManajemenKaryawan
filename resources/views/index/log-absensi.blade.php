<x-app-layout>
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-4 text-gray-800">Log Absensi</h2>

        <!-- Filter Section -->
        <div class="mb-6 bg-blue-100 p-6 rounded-lg shadow-md">
            <label for="filterTanggal" class="block text-sm font-medium text-gray-700">Filter Tanggal:</label>
            <input type="date" id="filterTanggal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">

            <label for="filterStatus" class="block mt-4 text-sm font-medium text-gray-700">Filter Status:</label>
            <select id="filterStatus" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                <option value="">Semua</option>
                <option value="Telat">Telat</option>
                <option value="Tepat Waktu">Tepat Waktu</option>
                <option value="Cuti">Cuti</option>
            </select>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto bg-blue-50 rounded-lg shadow-md">
            <table id="absensiTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Pulang</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($logs as $data)
                        @php
                            $isCuti = \App\Models\Cuti::where('user_id', $data->user_id)
                                        ->where('status', 'Approved')
                                        ->where('tanggal_mulai', '<=', $data->tanggal)
                                        ->where('tanggal_selesai', '>=', $data->tanggal)
                                        ->exists();
                        @endphp
                        <tr class="hover:bg-blue-50 transition duration-150 ease-in-out">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $data->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $data->tanggal }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $isCuti ? '-' : ($data->jam_masuk ?? 'Belum Checkin') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $isCuti ? '-' : ($data->jam_pulang ?? 'Belum Checkout') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $isCuti ? 'bg-red-100 text-red-800' : ($data->status == 'Telat' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800') }}">
                                    {{ $isCuti ? 'Cuti' : $data->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let table = new DataTable('#absensiTable');

            function filterTable() {
                let tanggal = document.getElementById('filterTanggal').value;
                let status = document.getElementById('filterStatus').value;

                table.columns(1).search(tanggal).columns(4).search(status).draw();
            }

            document.getElementById('filterTanggal').addEventListener('change', filterTable);
            document.getElementById('filterStatus').addEventListener('change', filterTable);
        });
    </script>
</x-app-layout>
