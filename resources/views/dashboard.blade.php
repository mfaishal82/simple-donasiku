<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            {{-- <button id="theme-toggle" class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-md">🌙 Dark Mode</button> --}}
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-400">Total Donasi yang Diterima</h3>
                <p class="text-2xl font-bold text-green-600">Rp 
                    <span id="total-donation"> {{ Auth::check() ? number_format(Auth::user()->donations()->where('status', 'completed')->sum('amount'), 0, ',', '.') : '0' }} </span>

                    <span id="donation-update" class="text-xl font-semibold text-green-500 transition-opacity opacity-0"></span>
                </p>
            </div>
            
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mt-6 p-6">
                <label for="profile-url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profile URL</label>
                <div class="flex mt-2">
                    <input type="text" id="profile-url" value="{{ url('user/' . Auth::user()->username) }}"
                        class="form-input rounded-md shadow-sm w-full border-gray-300 dark:bg-gray-700 dark:text-gray-200" readonly />
                    <button onclick="copyProfileUrl()" class="ml-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
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
                navigator.clipboard.writeText(profileUrl.value);
                alert('Copied to clipboard');
            }

            // document.getElementById('theme-toggle').addEventListener('click', function() {
            //     if (document.documentElement.classList.contains('dark')) {
            //         document.documentElement.classList.remove('dark');
            //         localStorage.setItem('theme', 'light');
            //         this.innerText = '🌙 Dark Mode';
            //     } else {
            //         document.documentElement.classList.add('dark');
            //         localStorage.setItem('theme', 'dark');
            //         this.innerText = '☀️ Light Mode';
            //     }
            // });

            // if (localStorage.getItem('theme') === 'dark') {
            //     document.documentElement.classList.add('dark');
            //     document.getElementById('theme-toggle').innerText = '☀️ Light Mode';
            // }
        </script>
    @endpush

    @push('script-head')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const userId = {{ Auth::id() }};
                const totalDonation = document.getElementById('total-donation');
                const donationUpdate = document.getElementById('donation-update');

                window.Echo.private(`donations.user.${userId}`)
                    .listen('DonationReceived', (data) => {
                        const donation = data.donation
                        const newAmount = parseInt(donation.amount)

                        let currentTotal = parseInt(totalDonation.innerText.replace(/\D/g, ''))
                        let updatedTotal = currentTotal + newAmount


                        donationUpdate.innerText = `+ Rp ${newAmount.toLocaleString()}`
                        donationUpdate.style.opacity = 1
                        donationUpdate.style.transition = 'opacity 0.5s ease-in-out'

                        setTimeout(() => {
                            donationUpdate.style.opacity = 0
                        }, 10000)

                        totalDonation.innerText = updatedTotal.toLocaleString()

                        Swal.fire({
                            title: '🎉 Donasi Baru Diterima!',
                            html: `<p><strong>Rp ${newAmount.toLocaleString('id-ID')}</strong> dari <strong>${donation.name}</strong></p>
                            <p>${donation.message}</p>
                            `,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 5000
                        })
                    });
            })
        </script>
    @endpush
</x-app-layout>
