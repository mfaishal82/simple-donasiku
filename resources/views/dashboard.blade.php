<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6 p-2">
                <label for="profile-url" class="block text-sm font-md text-gray-700 dark:text-white py-2 px-2">Profile URL</label>
                <div class="flex ml-1">
                    <input type="text" id="profile-url" value="{{ url('user/' . Auth::user()->username) }}"
                        class="form-input rounded-md shadow-sm mt-1 block w-full border-gray-300" readonly />
                    <button onclick="copyProfileUrl()"
                        class="ml-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Copy
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function copyProfileUrl() {
                const profileUrl = document.getElementById('profile-url');
                profileUrl.select();
                profileUrl.setSelectionRange(0, 99999);
                navigator.clipboard.writeText(profileUrl.value);
                alert('Copied to clipboard');
            }
        </script>
    @endpush
</x-app-layout>
