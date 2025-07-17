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
                    window.location.href = "{{ route('user.index') }}";
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
            {{ __('Registrasi Akun') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-50 dark:bg-gray-900 shadow sm:rounded-lg p-6">

                <!-- Form Registrasi -->
                <form method="POST" action="{{ route('user.store') }}">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                            :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- level --}}
                    <div class="mb-4">
                        <x-input-label for="level" :value="__('Level')" />
                        <select id="level" name="level"
                            class="mt-1 block w-full bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            style="color:white">
                            <option value="1" {{ old('level') == '1' ? 'selected' : '' }} style="color:white">Admin
                            </option>
                            <option value="2" {{ old('level') == '2' ? 'selected' : '' }} style="color:white">User
                            </option>
                        </select>
                        <x-input-error :messages="$errors->get('level')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />

                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                            autocomplete="new-password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                            name="password_confirmation" required autocomplete="new-password" />

                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ms-4">
                            {{ __('Register') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
