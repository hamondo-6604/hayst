@extends('layouts.driver')

@section('title', 'My Trips')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">My Trips</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">View assigned trips and send live GPS updates.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if(($upcomingTrips ?? collect())->isEmpty())
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                <i class="fa-regular fa-calendar-xmark text-4xl mb-3 opacity-50"></i>
                <p>You have no upcoming trips assigned.</p>
            </div>
        </div>
    @else
        <div class="grid gap-4">
            @foreach($upcomingTrips as $trip)
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 shadow-sm">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <div class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                Trip #{{ $trip->trip_code ?? $trip->id }} • {{ ucfirst($trip->status) }}
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mt-1">
                                {{ $trip->route?->originCity?->name ?? 'Origin' }} → {{ $trip->route?->destinationCity?->name ?? 'Destination' }}
                            </h3>
                            <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                {{ optional($trip->trip_date)->format('M d, Y') ?? 'No date' }} •
                                {{ optional($trip->departure_time)->format('h:i A') ?? 'No time' }}
                            </div>
                            <div class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                                Last GPS update:
                                <span id="last-update-{{ $trip->id }}">{{ $trip->last_location_updated_at?->diffForHumans() ?? 'Never' }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                class="px-3 py-2 text-sm font-semibold rounded-lg bg-primary-600 hover:bg-primary-700 text-white transition-colors"
                                onclick="pushCurrentLocation({{ $trip->id }}, '{{ route('driver.trips.location.update', $trip->id) }}')"
                            >
                                Send GPS Now
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    async function pushCurrentLocation(tripId, endpoint) {
        if (!navigator.geolocation) {
            alert('Geolocation is not supported in this browser.');
            return;
        }

        navigator.geolocation.getCurrentPosition(async (position) => {
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            const payload = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(payload)
                });

                if (!response.ok) {
                    alert('Could not update location for this trip.');
                    return;
                }

                const updateNode = document.getElementById(`last-update-${tripId}`);
                if (updateNode) {
                    updateNode.textContent = 'just now';
                }
                alert('GPS location updated successfully.');
            } catch (error) {
                alert('Location update failed. Please try again.');
            }
        }, () => {
            alert('Unable to fetch your location. Please enable GPS permissions.');
        }, {
            enableHighAccuracy: true,
            timeout: 10000
        });
    }
</script>
@endpush
