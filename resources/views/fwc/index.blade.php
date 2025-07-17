<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Frequent Woosher Card') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-50 dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg p-6">

                <div class="flex justify-end mb-4 gap-2">
                    <a href="{{ route('fwc.register') }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white text-sm font-medium rounded-md shadow hover:bg-indigo-700 dark:hover:bg-indigo-800 transition">
                        + Tambah Data
                    </a>

                    <button type="button" onclick="confirmExport()"
                        class="inline-flex items-center px-4 py-2 bg-green-600 dark:bg-green-700 text-white text-sm font-medium rounded-md shadow hover:bg-green-700 dark:hover:bg-green-800 transition">
                        ⬇️ Export Data
                    </button>
                </div>

                <table id="fwc-table"
                    class="min-w-full display stripe table-auto border border-gray-200 dark:border-gray-600 text-sm">
                    <thead class="bg-indigo-600 text-white dark:bg-indigo-700">
                        <tr>
                            <th class="px-3 py-2 text-left">ID FWC</th>
                            <th class="px-3 py-2 text-left">Nama</th>
                            <th class="px-3 py-2 text-left">NIK / No Passport</th>
                            <th class="px-3 py-2 text-left">Phone No</th>
                            <th class="px-3 py-2 text-left">E-mail</th>
                            <th class="px-3 py-2 text-left">Relasi</th>
                            <th class="px-3 py-2 text-left">Jenis</th>
                            <th class="px-3 py-2 text-left">Kuota</th>
                            <th class="px-3 py-2 text-left">Tgl Registrasi</th>
                            <th class="px-3 py-2 text-left">Tgl Expired</th>
                            <th class="px-3 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-gray-50 dark:bg-gray-900">
                        @forelse ($data as $item)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-800">
                                <td class="px-3 py-2 font-mono text-gray-700 dark:text-gray-200">{{ $item->id_fwc }}
                                </td>
                                <td class="px-3 py-2 text-gray-800 dark:text-gray-100">{{ $item->nama }}</td>
                                <td class="px-3 py-2 text-gray-700 dark:text-gray-200">{{ $item->id_num }}</td>
                                <td class="px-3 py-2 text-gray-700 dark:text-gray-200">{{ $item->no_hp }}</td>
                                <td class="px-3 py-2 text-gray-700 dark:text-gray-200">{{ $item->email }}</td>

                                <td class="px-3 py-2 text-gray-700 dark:text-gray-200">
                                    @php
                                        $relasiLabel = ['01' => 'Jaban', '02' => 'Jaka', '03' => 'Kaban'];
                                    @endphp
                                    {{ $relasiLabel[$item->relasi_fwc] ?? $item->relasi_fwc }}
                                </td>
                                <td class="px-3 py-2">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ $item->jenis_fwc == 'G'
                                            ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-300 dark:text-yellow-900'
                                            : 'bg-gray-100 text-gray-800 dark:bg-gray-300 dark:text-gray-900' }}">
                                        {{ $item->jenis_fwc == 'G' ? 'Gold' : 'Silver' }}
                                    </span>
                                </td>
                                <td class="px-3 py-2">
                                    @if ($item->kuota == 0)
                                        <span
                                            class="inline-block bg-red-100 text-red-800 dark:bg-red-300 dark:text-red-900 text-xs px-2 py-1 rounded-full font-semibold">Habis</span>
                                    @else
                                        <span
                                            class="inline-block bg-green-100 text-green-800 dark:bg-green-300 dark:text-green-900 text-xs px-2 py-1 rounded-full font-semibold">
                                            {{ $item->kuota }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 text-gray-600 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($item->tgl_reg)->format('d/m/Y') }}
                                </td>
                                <td class="px-3 py-2">
                                    @php $expired = \Carbon\Carbon::parse($item->tgl_exp)->isPast(); @endphp
                                    @if ($expired)
                                        <span
                                            class="inline-block bg-red-100 text-red-800 dark:bg-red-300 dark:text-red-900 text-xs px-2 py-1 rounded-full font-semibold">
                                            Expired ({{ \Carbon\Carbon::parse($item->tgl_exp)->format('d/m/Y') }})
                                        </span>
                                    @else
                                        <span
                                            class="inline-block bg-blue-100 text-blue-800 dark:bg-blue-300 dark:text-blue-900 text-xs px-2 py-1 rounded-full font-semibold">
                                            {{ \Carbon\Carbon::parse($item->tgl_exp)->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </td>
                                <!-- Tambah di <tbody> tiap baris -->
                                <!-- <tbody> -->
                                <td class="px-3 py-2">
                                    <button type="button" onclick="showSubData('{{ $item->id_fwc }}')"
                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        🔍
                                    </button>
                                </td>


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
        <script>
            function showSubData(id_fwc) {
                $.ajax({
                    url: `/fwc/subdata/${id_fwc}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        let html = '';

                        if (data.length === 0) {
                            html = '<i class="text-gray-500">Tidak ada data.</i>';
                        } else {
                            html += '<div style="text-align:left;">';
                            data.forEach((item, index) => {
                                html += `
                        <div style="margin-bottom:12px;">
                            <strong>No:</strong> ${index + 1}<br>
                            <strong>ID Pesanan:</strong> ${item.id_pesanan}<br>
                            <strong>Tgl Berangkat:</strong> ${item.tgl_departure}<br>
                            <strong>Tgl Redeem:</strong> ${item.created_at}<br>
                            <strong>User:</strong> ${item.user}
                        </div>
                        <hr style="border-color:#ccc;margin:10px 0;">
                    `;
                            });
                            html += '</div>';
                        }

                        Swal.fire({
                            title: `Detail Redeem FWC: ${id_fwc} <br> `,
                            html: `
<div style="max-height: 60vh; display: flex; flex-direction: column;">

    <div id="scrollable-content" style="flex: 1; overflow-y: auto; padding-right: 6px;">
        ${html}
    </div>

    <div class="mt-4 flex justify-end gap-2 sticky bottom-0 bg-white pt-2 border-t border-gray-300" style="padding-top: 10px;">
        <button type="button" id="cancelSwal" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
            Tutup
        </button>
        @if (Auth::user()->level == 1)
        <form id="deleteForm" action="/fwc/${id_fwc}" method="POST">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
            <button type="submit" id="confirmDelete" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                Hapus Data FWC
            </button>
        </form>
        @endif
    </div>
</div>
`,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'text-sm text-left'
                            },
                            didOpen: () => {
                                document.getElementById('cancelSwal').addEventListener('click', () => {
                                    Swal.close();
                                });

                                document.getElementById('deleteForm').addEventListener('submit',
                                    function(e) {
                                        e.preventDefault();

                                        Swal.fire({
                                            title: 'Yakin ingin menghapus?',
                                            text: 'Data FWC akan dihapus permanen.',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#e3342f',
                                            cancelButtonColor: '#6c757d',
                                            confirmButtonText: 'Ya, hapus!',
                                            cancelButtonText: 'Batal'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $.ajax({
                                                    url: this.action,
                                                    method: 'POST',
                                                    data: $(this).serialize(),
                                                    success: function() {
                                                        Swal.fire({
                                                            title: 'Berhasil!',
                                                            text: 'Data FWC berhasil dihapus.',
                                                            icon: 'success',
                                                            timer: 1500,
                                                            showConfirmButton: false
                                                        }).then(() => {
                                                            location
                                                                .reload(); // ✅ langsung refresh page
                                                        });
                                                    },
                                                    error: function() {
                                                        Swal.fire({
                                                            title: 'Gagal!',
                                                            text: 'Terjadi kesalahan saat menghapus.',
                                                            icon: 'error'
                                                        });
                                                    }
                                                });
                                            }
                                        });
                                    });
                            }
                        });


                        function closeModal() {
                            $('#subdataModal').addClass('hidden');
                        }

                        function closeModalOnOutside(event) {
                            if (event.target.id === 'subdataModal') {
                                closeModal();
                            }
                        }
                    }
                })
            }
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const deleteButtons = document.querySelectorAll('#delete-button');

                deleteButtons.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();

                        Swal.fire({
                            title: 'Yakin hapus data ini?',
                            text: 'Data FWC akan dihapus secara permanen!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#e3342f',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const form = btn.closest('form');

                                $.ajax({
                                    url: form.action,
                                    method: 'POST',
                                    data: $(form).serialize(),
                                    success: function() {
                                        Swal.fire({
                                            title: 'Berhasil!',
                                            text: 'Data berhasil dihapus.',
                                            icon: 'success',
                                            confirmButtonText: 'OK',
                                            timer: 2000,
                                            showConfirmButton: false
                                        });

                                        // Reload datatable (jika pakai DataTables)
                                        $('#fwc-table').DataTable().ajax.reload(
                                            null, false);
                                    },
                                    error: function() {
                                        Swal.fire({
                                            title: 'Gagal!',
                                            text: 'Terjadi kesalahan saat menghapus.',
                                            icon: 'error'
                                        });
                                    }
                                });
                            }
                        });
                    });
                });
            });

            function confirmExport() {
                Swal.fire({
                    title: 'Export Data',
                    text: 'Pilih jenis data yang ingin diexport:',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Main Data',
                    cancelButtonText: 'Subdata',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('fwc.export.main') }}";
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        window.location.href = "{{ route('fwc.export.sub') }}";
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
