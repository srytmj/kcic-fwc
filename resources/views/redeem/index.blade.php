<!-- jQuery UI CSS & JS -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showCancelButton: true,
                confirmButtonText: 'Tambah Lagi',
                cancelButtonText: 'Kembali ke Daftar',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.reload();
                } else {
                    window.location.href = "{{ route('fwc.index') }}";
                }
            });
        </script>
    @endif

    @if ($errors->has('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ $errors->first('error') }}',
            });
        </script>
    @endif
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Redeem Frequent Woosher Card
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-50 dark:bg-gray-900 p-6 shadow-md rounded-lg">

                <form method="POST" action="{{ route('redeem.store') }}" id="redeem-form">
                    @csrf

                    {{-- Input ID FWC --}}
                    <div class="mb-4">
                        <x-input-label for="id_fwc_input" :value="__('Cari ID FWC')" />
                        <x-text-input id="id_fwc_input" name="id_fwc_input" class="mt-1 block w-full"
                            placeholder="Ketik ID FWC..." autocomplete="off" />
                    </div>

                    {{-- Hidden input real ID untuk submission --}}
                    <input type="hidden" name="id_fwc" id="id_fwc" />

                    {{-- Info FWC --}}
                    <div class="mb-4">
                        <x-input-label value="Info FWC" />
                        <div id="info-fwc" class="text-sm text-gray-700 dark:text-gray-100 italic">
                            Silakan cari ID FWC terlebih dahulu.
                        </div>
                    </div>

                    {{-- Input ID Pesanan --}}
                    <div class="mb-6">
                        <x-input-label for="id_pesanan" :value="__('ID Pesanan')" />
                        <x-text-input id="id_pesanan" name="id_pesanan" class="mt-1 block w-full"
                            placeholder="Contoh: GA1000010" required />
                    </div>

                    {{-- Tanggal Keberangkatan --}}
                    <div class="mb-6">
                        <x-input-label for="tgl_departure" :value="__('Tanggal Keberangkatan')" />
                        <x-text-input id="tgl_departure" name="tgl_departure" type="date" class="mt-1 block w-full"
                            required />
                    </div>

                    {{-- Tombol --}}
                    <div class="flex justify-end">
                        <x-primary-button id="submit-btn" disabled>Redeem</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let idNum = null;

        const relasiMap = {
            '01': 'Jaban',
            '02': 'Jaka',
            '03': 'Kaban'
        };

        $('#id_fwc_input').autocomplete({
            minLength: 2,
            source: function(request, response) {
                $.getJSON("{{ route('redeem.autocompleteByIDFWC') }}", {
                    term: request.term
                }, response);
            },
            select: function(event, ui) {
                $('#id_fwc_input').val(ui.item.id_fwc);
                $('#id_fwc').val(ui.item.id_fwc);

                const relasi = relasiMap[ui.item.relasi_fwc] || ui.item.relasi_fwc;
                let info = `Nama: ${ui.item.nama}<br>`;
                info += `Relasi: ${relasi}<br>`;
                info += `Kuota: ${ui.item.kuota}<br>`;
                info += `Expired: ${ui.item.tgl_exp}`;

                if (ui.item.expired || ui.item.kuota <= 0) {
                    info += `<br><span class="text-red-600">⚠️ Tidak bisa di-redeem</span>`;
                    $('#submit-btn').prop('disabled', true);
                } else {
                    $('#submit-btn').prop('disabled', false);
                }

                $('#info-fwc').html(info);
                return false;
            }
        });
    </script>
</x-app-layout>
