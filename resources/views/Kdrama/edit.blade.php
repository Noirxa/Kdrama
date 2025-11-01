<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Review bewerken') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <a href="{{ route('kdramas.show', $review->kdrama_id) }}"
                       class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 mb-4">
                        ← Terug naar K-Drama
                    </a>

                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600 dark:text-red-400">
                                {{ __('Oeps! Er ging iets mis met je invoer:') }}
                            </div>
                            <ul class="mt-3 list-disc list-inside text-sm text-red-600 dark:text-red-400">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('reviews.update', $review->id) }}" method="POST" class="mt-4 space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="rating" :value="__('Beoordeling (rating)')" />
                            <x-text-input id="rating" class="block mt-1 w-full"
                                          type="number"
                                          name="rating"
                                          :value="old('rating', $review->rating)"
                                          required
                                          autofocus />
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Geef een cijfer (bijv: 8 voor een 8/10).</p>
                            <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="comment" :value="__('Opmerking (comment)')" />
                            <textarea id="comment" name="comment" rows="4"
                                      class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                      required>{{ old('comment', $review->comment) }}</textarea>
                            <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button>
                                {{ __('Wijzigingen opslaan') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
