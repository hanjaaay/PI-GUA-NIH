<section class="space-y-6">
    <header class="mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
            {{ __('Hapus Akun') }}
        </h2>

        <p class="mt-2 text-base text-gray-600 dark:text-gray-400">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan data di dalamnya akan terhapus secara permanen. Sebelum menghapus, unduh semua data yang ingin Anda simpan.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Hapus Akun') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Apakah Anda yakin ingin menghapus akun Anda?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Setelah akun Anda dihapus, semua sumber daya dan data di dalamnya akan terhapus secara permanen. Masukkan kata sandi Anda untuk mengonfirmasi.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Kata Sandi') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full rounded-md"
                    placeholder="{{ __('Kata Sandi') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Hapus Akun') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>