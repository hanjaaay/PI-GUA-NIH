<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Hero Section dengan Ajakan Aksi --}}
            <div class="bg-white rounded-2xl shadow-2xl p-8 sm:p-12 mb-10 text-center">
                <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 leading-tight mb-4">
                    Halo, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-8">
                    Petualangan Anda selanjutnya menanti. Temukan pengalaman baru dan pesan tiketnya sekarang.
                </p>
                {{-- Hidden: Removed redundant event exploration CTA for focused single-concert UX --}}
            </div>
            
            {{-- Kartu Pemesanan Terbaru --}}
            @if ($latestBooking)
            <div class="bg-white rounded-2xl shadow-xl p-8 sm:p-10">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Pemesanan Terakhir Anda</h2>
                    <a href="{{ route('bookings.index') }}" class="text-base font-medium text-indigo-600 hover:underline">
                        Lihat Semua
                    </a>
                </div>

                <div class="flex flex-col md:flex-row items-center space-y-6 md:space-y-0 md:space-x-8">
                    <div class="flex-shrink-0 w-full md:w-auto h-48 md:h-32 md:w-32 rounded-lg overflow-hidden shadow-md">
                        <img src="{{ asset('storage/' . $latestBooking->touristAttraction->thumbnail) }}" alt="{{ $latestBooking->touristAttraction->name }}" class="object-cover w-full h-full">
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">
                            {{ $latestBooking->touristAttraction->name }}
                        </h3>
                        <p class="text-gray-500 mb-2">
                            Tanggal Kunjungan: <span class="font-semibold">{{ \Carbon\Carbon::parse($latestBooking->visit_date)->format('d F Y') }}</span>
                        </p>
                        <p class="text-gray-500 mb-2">
                            Jumlah: <span class="font-semibold">{{ $latestBooking->quantity }} tiket</span>
                        </p>
                        <div class="flex items-center space-x-4 mt-4">
                            <span class="px-3 py-1 text-sm font-semibold rounded-full
                                @if($latestBooking->status == 'paid') bg-green-100 text-green-800 @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($latestBooking->status) }}
                            </span>
                            <a href="{{ route('bookings.show', $latestBooking) }}" class="text-indigo-600 font-medium hover:underline">
                                Lihat Detail &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @else
            {{-- Pesan jika belum ada pemesanan --}}
            <div class="bg-white rounded-2xl shadow-xl p-8 sm:p-10 text-center">
                <p class="text-lg text-gray-500">
                    Anda belum memiliki pemesanan. Mulailah petualangan Anda sekarang!
                </p>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>