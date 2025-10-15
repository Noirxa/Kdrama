<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ $kdrama->title }}
        </h2>
    </x-slot>

    <div class="p-6 bg-blue-900 text-white min-h-screen">
        {{-- K-Drama Details --}}
        <div class="bg-blue-800/50 p-6 rounded-lg shadow-lg mb-8">
            <ul class="space-y-4">
                @foreach($kdrama->getAttributes() as $key => $value)
                    @if($key === 'image_url')
                        <li>
                            <strong class="text-blue-300 block mb-2">{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                            <img src="{{ $value }}" alt="{{ $kdrama->title }}" class="max-w-xs mt-2 rounded-lg shadow-md">
                        </li>
                    @elseif(!in_array($key, ['id', 'created_at', 'updated_at']))
                        <li>
                            <strong class="text-blue-300">{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> <span class="ml-2">{{ $value }}</span>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>


        {{-- Reviews Section --}}
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4 border-b-2 border-blue-700 pb-2">Reviews</h2>
            <div class="space-y-6">
                @forelse ($kdrama->reviews as $review)
                    <div class="bg-blue-800/50 p-4 rounded-lg">
                        <p class="font-bold text-lg">Score: <span class="text-yellow-400">{{ $review->rating }}/10</span></p>
                        <p class="mt-2">{{ $review->comment }}</p>
                        <small class="block text-right text-blue-400 mt-2">Written on: {{ $review->created_at->format('d-m-Y') }}</small>
                    </div>
                @empty
                    <p class="bg-blue-800/50 p-4 rounded-lg">There are no reviews for this K-drama yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Add Review Form --}}
        <div class="bg-blue-800/50 p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-semibold mb-4">Leave a review</h3>
            <form action="{{ route('reviews.store', $kdrama) }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="rating" class="block mb-2 font-medium text-blue-300">Rating (1-10):</label>
                    {{-- Changed to number input with min/max values and added styling --}}
                    <input type="number" id="rating" name="rating" min="1" max="10" required
                           class="w-full bg-blue-800 text-white rounded border border-blue-700 focus:ring-blue-500 focus:border-blue-500 p-2">
                </div>

                <div>
                    <label for="comment" class="block mb-2 font-medium text-blue-300">Comment:</label>
                    {{-- Added styling to textarea --}}
                    <textarea id="comment" name="comment" rows="4" required
                              class="w-full bg-blue-800 text-white rounded border border-blue-700 focus:ring-blue-500 focus:border-blue-500 p-2"></textarea>
                </div>

                <div>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-lg text-white font-semibold transition duration-200">
                        Post Review
                    </button>
                </div>
            </form>
        </div>

        {{-- Back to overview link --}}
        <a href="{{ route('kdramas.index') }}" class="inline-block mt-8 text-blue-300 hover:text-blue-100 transition duration-200">
            ← Back to overview
        </a>
    </div>
</x-app-layout>
