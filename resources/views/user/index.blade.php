<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Users Management') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-50 dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg p-6">

                @if (Auth::user()->level == '1')
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('user.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white text-sm font-medium rounded-md shadow hover:bg-indigo-700 dark:hover:bg-indigo-800 transition">
                            + Tambah Data
                        </a>
                    </div>
                @endif

                <table id="fwc-table"
                    class="min-w-full display stripe table-auto border border-gray-200 dark:border-gray-600 text-sm">
                    <thead class="bg-indigo-600 text-white dark:bg-indigo-700">
                        <tr>
                            <th class="px-3 py-2 text-left">ID</th>
                            <th class="px-3 py-2 text-left">Username</th>
                            <th class="px-3 py-2 text-left">Email</th>
                            <th class="px-3 py-2 text-left">Level</th>
                            @if (Auth::user()->level == '1')
                                <th class="px-3 py-2 text-left">Edit</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-gray-50 dark:bg-gray-900">
                        @forelse ($data as $item)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-800">
                                <td class="px-3 py-2 font-mono text-gray-700 dark:text-gray-200">{{ $item->id }}</td>
                                <td class="px-3 py-2 text-gray-800 dark:text-gray-100">{{ $item->name }}</td>
                                <td class="px-3 py-2 text-gray-700 dark:text-gray-200">{{ $item->email }}</td>
                                <td class="px-3 py-2 text-gray-700 dark:text-gray-200">{{ $item->level == '1' ? 'Admin' : 'User' }}</td>
                                @if (Auth::user()->level == '1')
                                    <td class="px-3 py-2">
                                        <a href="{{ route('user.edit', $item->id) }}"
                                            class="text-indigo-600 dark:text-indigo-400 hover:underline">Edit</a>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-gray-500 dark:text-gray-400 italic">
                                    Belum ada data.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                let table = $('#fwc-table').DataTable({
                    responsive: true,
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ entri",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                        paginate: {
                            first: "Awal",
                            last: "Akhir",
                            next: "›",
                            previous: "‹"
                        },
                        zeroRecords: "Tidak ditemukan data yang cocok"
                    }
                });

                // === Tambahin Tailwind dark style ===
                $('div.dataTables_wrapper').addClass('text-white');
                $('.dataTables_filter input').addClass(
                    'bg-gray-800 border border-gray-600 text-white rounded-md px-2 py-1');
                $('.dataTables_length select').addClass(
                    'bg-gray-800 border border-gray-600 text-white rounded-md px-2 py-1');
                $('.dataTables_paginate').addClass('mt-4');
                $('.dataTables_paginate .paginate_button').addClass(
                    'bg-gray-800 border border-gray-600 text-white px-3 py-1 mx-1 rounded hover:bg-gray-700');

                // Table header
                $('#fwc-table thead').addClass('bg-gray-800 text-white');

                // Table rows
                $('#fwc-table tbody tr').addClass('bg-gray-900 text-white');
            });
        </script>
    @endpush
</x-app-layout>
