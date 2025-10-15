{{--<h1>{{ $kdrama->title }}</h1>--}}
{{--<p>Genre: {{ $kdrama->genre }}</p>--}}
{{--<p>Aantal afleveringen: {{ $kdrama->episodes }}</p>--}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            K-Dramas
        </h2>
    </x-slot>

    <div class="p-6 bg-blue-900 text-white min-h-screen">
        @foreach($kdramas as $kdrama)
            <h1 class="text-xl font-semibold">
                <a href="{{ route('kdramas.show', $kdrama) }}" class="text-white hover:text-blue-300">
                    {{ $kdrama->title }}
                </a>
            </h1>
            <hr class="border-gray-500 my-2">
        @endforeach
    </div>
</x-app-layout>


