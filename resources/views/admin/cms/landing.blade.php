@extends('layouts.admin')
@section('title', 'Landing Page CMS')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Landing Page CMS</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage the content that appears on the public homepage.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.cms.landing.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        <input type="hidden" name="hero_slides_submitted" value="1">

        {{-- ══════════════ HERO SECTION ══════════════ --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
            <div class="bg-slate-50 dark:bg-slate-900/50 px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <h2 class="text-lg font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-images text-primary-500"></i> Hero Carousel
                </h2>
                <button type="button" onclick="addHeroSlide()" class="text-xs font-bold text-primary-600 dark:text-primary-400 hover:underline">
                    + Add Slide
                </button>
            </div>
            
            <div class="p-6" id="hero-slides-container">
                @php
                    $slides = $settings['hero_slides'] ?? [];
                    if (empty($slides)) {
                        $slides = [
                            [
                                'badge' => 'Instant e-ticket confirmation',
                                'title' => 'Travel Mindanao<br><span class="text-primary-400">Your Way</span>',
                                'subtitle' => 'Book intercity bus trips in seconds.',
                                'image' => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&q=80&w=2000'
                            ],
                            [
                                'badge' => 'Premium Comfort',
                                'title' => 'First Class<br><span class="text-primary-400">Experience</span>',
                                'subtitle' => 'Enjoy spacious seating and full air-conditioning.',
                                'image' => 'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?auto=format&fit=crop&q=80&w=2000'
                            ]
                        ];
                    }
                @endphp

                @foreach($slides as $index => $slide)
                    <div class="slide-item mb-6 pb-6 border-b border-slate-100 dark:border-slate-700 last:border-0 last:mb-0 last:pb-0 relative">
                        <button type="button" onclick="this.closest('.slide-item').remove()" class="absolute top-0 right-0 text-red-500 hover:text-red-700 text-sm">
                            <i class="fa-solid fa-trash"></i> Remove
                        </button>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Badge Text</label>
                                <input type="text" name="hero_slides[{{ $index }}][badge]" value="{{ $slide['badge'] ?? '' }}" class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 text-sm" placeholder="e.g. Instant e-ticket confirmation">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Title (HTML allowed)</label>
                                <input type="text" name="hero_slides[{{ $index }}][title]" value="{{ $slide['title'] ?? '' }}" class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 text-sm" placeholder="e.g. Travel Mindanao">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Subtitle</label>
                                <textarea name="hero_slides[{{ $index }}][subtitle]" rows="2" class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 text-sm">{{ $slide['subtitle'] ?? '' }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Background Image</label>
                                <div class="flex items-center gap-4">
                                    @if(!empty($slide['image']))
                                        <img src="{{ $slide['image'] }}" class="w-20 h-12 object-cover rounded-lg border border-slate-200 dark:border-slate-700">
                                        <input type="hidden" name="hero_slides[{{ $index }}][existing_image]" value="{{ $slide['image'] }}">
                                    @endif
                                    <input type="file" name="hero_slides[{{ $index }}][image]" accept="image/*" class="text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ══════════════ POPULAR ROUTES ══════════════ --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
            <div class="bg-slate-50 dark:bg-slate-900/50 px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-lg font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-fire text-orange-500"></i> Popular Routes Section
                </h2>
            </div>
            <div class="p-6 grid gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Section Title</label>
                    <input type="text" name="popular_routes_title" value="{{ $settings['popular_routes_title'] ?? 'Popular Routes' }}" class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Section Subtitle</label>
                    <input type="text" name="popular_routes_subtitle" value="{{ $settings['popular_routes_subtitle'] ?? 'Our most-booked intercity routes — reserve early for the best fares.' }}" class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 text-sm">
                </div>
            </div>
        </div>

        {{-- ══════════════ HOW IT WORKS ══════════════ --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
            <div class="bg-slate-50 dark:bg-slate-900/50 px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <h2 class="text-lg font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-list-ol text-blue-500"></i> How It Works
                </h2>
            </div>
            
            <div class="p-6">
                @php
                    $steps = $settings['how_it_works'] ?? [
                        ['icon' => 'search', 'title' => 'Search Your Trip', 'desc' => 'Enter your origin, destination, and travel date.'],
                        ['icon' => 'armchair', 'title' => 'Pick Your Seat', 'desc' => 'View the live seat map.'],
                        ['icon' => 'credit-card', 'title' => 'Pay Securely', 'desc' => 'GCash, Maya, card, or OTC.'],
                        ['icon' => 'ticket', 'title' => 'Board & Ride', 'desc' => 'Show your QR e-ticket at the terminal gate.']
                    ];
                @endphp
                <div class="grid md:grid-cols-2 gap-6">
                    @foreach($steps as $index => $step)
                    <div class="border border-slate-100 dark:border-slate-700 rounded-xl p-4">
                        <div class="mb-3">
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Step {{ $index + 1 }} Title</label>
                            <input type="text" name="how_it_works[{{ $index }}][title]" value="{{ $step['title'] ?? '' }}" class="w-full rounded-lg border-slate-200 dark:border-slate-600 dark:bg-slate-700 text-sm">
                        </div>
                        <div class="mb-3">
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Step {{ $index + 1 }} Description</label>
                            <textarea name="how_it_works[{{ $index }}][desc]" rows="2" class="w-full rounded-lg border-slate-200 dark:border-slate-600 dark:bg-slate-700 text-sm">{{ $step['desc'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Lucide Icon Name</label>
                            <input type="text" name="how_it_works[{{ $index }}][icon]" value="{{ $step['icon'] ?? '' }}" class="w-full rounded-lg border-slate-200 dark:border-slate-600 dark:bg-slate-700 text-sm">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ══════════════ FAQS ══════════════ --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
            <div class="bg-slate-50 dark:bg-slate-900/50 px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <h2 class="text-lg font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-circle-question text-purple-500"></i> Travel FAQs
                </h2>
                <button type="button" onclick="addFaq()" class="text-xs font-bold text-primary-600 dark:text-primary-400 hover:underline">
                    + Add FAQ
                </button>
            </div>
            
            <div class="p-6 grid gap-4" id="faqs-container">
                @php
                    $faqs = $settings['faqs'] ?? [
                        ['q' => 'What is your luggage policy?', 'a' => 'Each passenger is allowed 1 hand-carry bag (up to 7kg)...']
                    ];
                @endphp
                @foreach($faqs as $index => $faq)
                    <div class="faq-item border border-slate-100 dark:border-slate-700 rounded-xl p-4 relative">
                        <button type="button" onclick="this.closest('.faq-item').remove()" class="absolute top-2 right-4 text-red-500 hover:text-red-700 text-xs font-bold">
                            Remove
                        </button>
                        <div class="mb-3 pr-16">
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Question</label>
                            <input type="text" name="faqs[{{ $index }}][q]" value="{{ $faq['q'] ?? '' }}" class="w-full rounded-lg border-slate-200 dark:border-slate-600 dark:bg-slate-700 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Answer</label>
                            <textarea name="faqs[{{ $index }}][a]" rows="2" class="w-full rounded-lg border-slate-200 dark:border-slate-600 dark:bg-slate-700 text-sm">{{ $faq['a'] ?? '' }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ══════════════ CTA BANNER ══════════════ --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
            <div class="bg-slate-50 dark:bg-slate-900/50 px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-lg font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                    <i class="fa-solid fa-bullhorn text-emerald-500"></i> Bottom CTA Banner
                </h2>
            </div>
            <div class="p-6 grid gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Title</label>
                    <input type="text" name="cta_title" value="{{ $settings['cta_title'] ?? 'Ready to ride?' }}" class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Description</label>
                    <input type="text" name="cta_description" value="{{ $settings['cta_description'] ?? 'Search from our routes and book your seat in under 2 minutes.' }}" class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 text-sm">
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4 pb-12">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition-colors flex items-center gap-2">
                <i class="fa-solid fa-save"></i> Save CMS Changes
            </button>
        </div>
    </form>
</div>

<script>
    let slideCount = {{ count($slides ?? []) }};
    function addHeroSlide() {
        const container = document.getElementById('hero-slides-container');
        const i = slideCount++;
        const html = `
            <div class="slide-item mb-6 pb-6 border-b border-slate-100 dark:border-slate-700 last:border-0 last:mb-0 last:pb-0 relative">
                <button type="button" onclick="this.closest('.slide-item').remove()" class="absolute top-0 right-0 text-red-500 hover:text-red-700 text-sm">
                    <i class="fa-solid fa-trash"></i> Remove
                </button>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Badge Text</label>
                        <input type="text" name="hero_slides[${i}][badge]" class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 text-sm" placeholder="e.g. Instant e-ticket confirmation">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Title (HTML allowed)</label>
                        <input type="text" name="hero_slides[${i}][title]" class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 text-sm" placeholder="e.g. Travel Mindanao">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Subtitle</label>
                        <textarea name="hero_slides[${i}][subtitle]" rows="2" class="w-full rounded-xl border-slate-200 dark:border-slate-600 dark:bg-slate-700 text-sm"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Background Image</label>
                        <input type="file" name="hero_slides[${i}][image]" accept="image/*" class="text-sm">
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }

    let faqCount = {{ count($faqs ?? []) }};
    function addFaq() {
        const container = document.getElementById('faqs-container');
        const i = faqCount++;
        const html = `
            <div class="faq-item border border-slate-100 dark:border-slate-700 rounded-xl p-4 relative">
                <button type="button" onclick="this.closest('.faq-item').remove()" class="absolute top-2 right-4 text-red-500 hover:text-red-700 text-xs font-bold">
                    Remove
                </button>
                <div class="mb-3 pr-16">
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Question</label>
                    <input type="text" name="faqs[${i}][q]" class="w-full rounded-lg border-slate-200 dark:border-slate-600 dark:bg-slate-700 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Answer</label>
                    <textarea name="faqs[${i}][a]" rows="2" class="w-full rounded-lg border-slate-200 dark:border-slate-600 dark:bg-slate-700 text-sm"></textarea>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }
</script>
@endsection
