<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            K-Dramas
        </h2>
    </x-slot>

    <!-- Extra CSS voor placeholder en kleine styling fixes -->
    <style>
        /* placeholder kleur zichtbaar op donkere achtergrond */
        input::placeholder, textarea::placeholder {
            color: #cbd5e1; /* lichtgrijs/blauwachtig */
        }

        /* zorgt dat optie-achtergrond en tekst goed zichtbaar zijn in sommige browsers */
        select option {
            background-color: #001F3F;
            color: white;
        }


    </style>

    <div class="p-6 bg-blue-900 text-white min-h-screen">
        <!-- Zoek & Filter Formulier (gestyled zoals jouw voorbeeld) -->
        <form method="GET" action="{{ route('kdramas.index') }}" class="mb-6 flex flex-col sm:flex-row gap-3">

            <!--  Zoekveld (donkerblauw, witte tekst) -->
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Zoek op titel of beschrijving..."
                style="background-color:#001F3F; color:white; padding:8px; border:none; border-radius:5px; width:100%;"
                class="form-search"
            >

            <!--  Genre filter (donkerblauw, witte tekst) -->
            <select
                name="genre"
                style="background-color:#001F3F; color:white; padding:8px; border:none; border-radius:5px; width:100%;"
                class="form-genre"
            >
                <option value="" {{ request('genre') == '' ? 'selected' : '' }}>Alle genres</option>
                @foreach($genres as $genre)
                    <option
                        value="{{ $genre }}"
                        {{ request('genre') == $genre ? 'selected' : '' }}
                    >
                        {{ $genre }}
                    </option>
                @endforeach
            </select>

            <!-- 🔘 Zoekknop (iets lichter blauw zoals jouw Opslaan knop) -->
            <button
                type="submit"
                style="background-color:#003399; color:white; padding:8px 16px; border:none; border-radius:5px; cursor:pointer;"
            >
                Zoeken
            </button>
        </form>

        <!--  Resultatenlijst -->
        @forelse($kdramas as $kdrama)
            <h1 class="text-xl font-semibold">
                <a href="{{ route('kdramas.show', $kdrama) }}" class="text-white hover:text-blue-300">
                    {{ $kdrama->title }}
                    <span class="text-sm text-gray-300">({{ $kdrama->genre }})</span>
                </a>
            </h1>
            <hr class="border-gray-500 my-2">
        @empty
            <p class="text-gray-300">Geen K-dramas gevonden.</p>
        @endforelse

        <!-- 🔗 Paginanavigatie -->
        <div class="mt-4">
            {{ $kdramas->links() }}
        </div>
    </div>
</x-app-layout>
