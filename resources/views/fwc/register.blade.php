<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                icon: 'success',
                showCancelButton: true,
                confirmButtonText: 'Tambah Lagi',
                cancelButtonText: 'Kembali ke Daftar',
                reverseButtons: true
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
                title: 'Gagal!',
                text: '{{ $errors->first('error') }}',
                icon: 'error',
                confirmButtonText: 'Tutup'
            });
        </script>
    @endif

    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Registrasi Frequent Woosher Card') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-50 dark:bg-gray-900 shadow sm:rounded-lg p-6">

                <!-- Form Registrasi -->
                <form method="POST" action="{{ route('fwc.store') }}">
                    @csrf

                    <!-- ID FWC -->
                    <div class="mb-4">
                        <x-input-label for="id_fwc" :value="__('ID FWC')" />
                        <x-text-input id="id_fwc" name="id_fwc" type="text" class="mt-1 block w-full"
                            :value="old('id_fwc')" />
                        <x-input-error :messages="$errors->get('id_fwc')" class="mt-2" />
                    </div>

                    <!-- Nama -->
                    <div class="mb-4">
                        <x-input-label for="nama" :value="__('Nama')" />
                        <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full"
                            :value="old('nama')" required autofocus />
                        <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                    </div>

                    <!-- No Identitas -->
                    <div class="mb-4">
                        <x-input-label for="id_num" :value="__('NIK / No Passport')" />
                        <x-text-input id="id_num" name="id_num" type="text" class="mt-1 block w-full"
                            :value="old('id_num')" required />
                        <x-input-error :messages="$errors->get('id_num')" class="mt-2" />
                    </div>
                    
                    <!-- Email Optional -->
                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Email (Opsional)')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block
                            w-full" :value="old('email')" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- No HP Optional -->
                    <div class="mb-4">
                        <x-input-label for="no_hp" :value="__('No HP (Opsional)')" />
                        <x-text-input id="no_hp" name="no_hp" type="text" class="mt-1 block
                            w-full" :value="old('no_hp')" />
                        <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
                    </div>

                    <!-- Relasi -->
                    <div class="mb-4">
                        <x-input-label for="relasi_fwc" :value="__('Relasi')" />
                        <select name="relasi_fwc" id="relasi_fwc"
                            class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            required>
                            <option value="" hidden>-- Pilih Relasi --</option>
                            <option value="01">Jaban</option>
                            <option value="02">Jaka</option>
                            <option value="03">Kaban</option>
                        </select>
                        <x-input-error :messages="$errors->get('relasi_fwc')" class="mt-2" />
                    </div>

                    <!-- Jenis FWC -->
                    <div class="mb-6">
                        <x-input-label for="jenis_fwc" :value="__('Jenis FWC')" />
                        <select name="jenis_fwc" id="jenis_fwc"
                            class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            required>
                            <option value="" hidden>-- Pilih Jenis --</option>
                            <option value="G">Gold</option>
                            <option value="S">Silver</option>
                        </select>
                        <x-input-error :messages="$errors->get('jenis_fwc')" class="mt-2" />
                    </div>

                    <!-- Tombol Submit -->
                    <div class="flex justify-end">
                        <x-primary-button>{{ __('Daftarkan') }}</x-primary-button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
