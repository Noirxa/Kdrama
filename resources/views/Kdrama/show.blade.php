<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ $kdrama->title }}
        </h2>
    </x-slot>

    <div class="p-6 bg-blue-900 text-white min-h-screen">
        {{-- Flash messages (success / error) --}}
        @if(session('success'))
            <div class="mb-4 p-3 rounded bg-green-700 text-white">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3 rounded bg-red-700 text-white">
                {{ session('error') }}
            </div>
        @endif

        {{-- K-Drama Details --}}
        <div class="bg-blue-800/50 p-6 rounded-lg shadow-lg">
            <ul class="space-y-4">
                @foreach($kdrama->getAttributes() as $key => $value)
                    @if($key === 'image_url' && $value)
                        <li>
                            <strong class="text-blue-300 block mb-2">{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                            <img src="{{ $value }}" alt="{{ $kdrama->title }}" class="max-w-xs mt-2 rounded-lg shadow-md">
                        </li>
                    @elseif(!in_array($key, ['id', 'created_at', 'updated_at', 'user_id']))
                        <li>
                            <strong class="text-blue-300">{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                            <span class="ml-2">{{ $value }}</span>
                        </li>
                    @endif
                @endforeach

                {{-- Optioneel toon maker (als aanwezig) --}}
                @if(isset($kdrama->user))
                    <li>
                        <strong class="text-blue-300">Aangemaakt door:</strong>
                        <span class="ml-2">{{ $kdrama->user->name }}</span>
                    </li>
                @endif
            </ul>
        </div>

        <hr class="my-8 border-blue-700">

        {{-- Acties voor Kdrama: Bewerken / Verwijderen --}}
        <div class="mb-6">
            @auth
                @if(isset($kdrama->user_id) && $kdrama->user_id === auth()->id())
                    <a href="{{ route('kdramas.edit', $kdrama->id) }}"
                       class="inline-block mr-2 px-4 py-2 rounded bg-blue-600 hover:bg-blue-500">
                        ✏️ Bewerken
                    </a>

                    <form action="{{ route('kdramas.destroy', $kdrama->id) }}" method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je deze Kdrama wilt verwijderen?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-block px-4 py-2 rounded bg-red-600 hover:bg-red-500">
                            🗑️ Verwijderen
                        </button>
                    </form>
                @endif
            @endauth
        </div>

        <hr class="my-8 border-blue-700">

        {{-- Reviews Section --}}
        <div>
            <h2 class="text-2xl font-bold mb-4">Recensies</h2>
            <div class="space-y-6">
                @forelse ($kdrama->reviews as $review)
                    <div class="bg-blue-800/50 p-4 rounded-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-bold text-lg">Score: <span class="text-yellow-400">{{ $review->rating }}/10</span></p>
                                <p class="mt-2">{{ $review->comment }}</p>
                                <small class="block text-blue-400 mt-2">Geschreven op: {{ $review->created_at->format('d-m-Y') }}</small>
                                @if(isset($review->user))
                                    <small class="block text-blue-300">Door: {{ $review->user->name }}</small>
                                @endif
                            </div>

                            {{-- Acties voor review: bewerken / verwijderen (alleen auteur) --}}
                            <div class="ml-4">
                                @auth
                                    @if($review->user_id === auth()->id())
                                        <a href="{{ route('reviews.edit', $review->id) }}" class="inline-block px-3 py-1 rounded bg-blue-600 hover:bg-blue-500 text-white mb-2">
                                            ✏️ Bewerken
                                        </a>

                                        <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="inline" onsubmit="return confirm('Weet je zeker dat je deze recensie wilt verwijderen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-block px-3 py-1 rounded bg-red-600 hover:bg-red-500 text-white">
                                                🗑️ Verwijderen
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="bg-blue-800/50 p-4 rounded-lg">Er zijn nog geen recensies voor deze K-drama.</p>
                @endforelse
            </div>
        </div>

        <hr class="my-8 border-blue-700">

        {{-- Add Review Form (alleen voor ingelogde gebruikers) --}}
        <div>
            <h3 class="text-xl font-semibold mb-4">Laat een recensie achter</h3>

            @auth
                <form action="{{ route('reviews.store', $kdrama) }}" method="POST">
                    @csrf

                    {{-- Rating --}}
                    <div class="mb-4">
                        <label for="rating">Beoordeling (1-10):</label><br>
                        <input type="number" id="rating" name="rating" min="1" max="10" required
                               value="{{ old('rating') }}"
                               style="background-color:#001F3F; color:white; padding:8px; border:none; border-radius:5px; width:100%;">
                        @error('rating')
                        <p class="text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Comment --}}
                    <div class="mb-4">
                        <label for="comment">Opmerking:</label><br>
                        <textarea id="comment" name="comment" rows="4" required
                                  style="background-color:#001F3F; color:white; padding:8px; border:none; border-radius:5px; width:100%;">{{ old('comment') }}</textarea>
                        @error('comment')
                        <p class="text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            style="background-color:#003399; color:white; padding:8px 16px; border:none; border-radius:5px; cursor:pointer;">
                        Plaats Recensie
                    </button>
                </form>
            @else
                <p class="bg-blue-800/50 p-4 rounded-lg">
                    Je moet <a href="{{ route('login') }}" class="text-yellow-300 underline">inloggen</a> om een recensie te plaatsen.
                </p>
            @endauth
        </div>

        <br>

        {{-- Back to overview link --}}
        <a href="{{ route('kdramas.index') }}" class="inline-block mt-4 text-blue-300 hover:text-blue-100">
            ← Terug naar overzicht
        </a>
    </div>
</x-app-layout>
