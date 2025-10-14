{{--<h1>{{ $kdrama->title }}</h1>--}}
{{--<p>Genre: {{ $kdrama->genre }}</p>--}}
{{--<p>Aantal afleveringen: {{ $kdrama->episodes }}</p>--}}

@foreach($kdramas as $kdrama)

    <h1><a href="{{ route('kdramas.show', $kdrama) }}">{{ $kdrama->title }}</a></h1>
{{--    <p>Genre: {{ $kdrama->genre }}</p>--}}
{{--    <p>Release year: {{ $kdrama->release_year }}</p>--}}
    <hr>

@endforeach

{{--<!DOCTYPE html>--}}
{{--<html>--}}
{{--<head>--}}
{{--    <title>K-Dramas</title>--}}
{{--</head>--}}
{{--<body>--}}
{{--<h1>Alle K-Dramas</h1>--}}
{{--<ul>--}}
{{--    @foreach($kdramas as $kdrama)--}}
{{--        <li>--}}
{{--            <h2>{{ $kdrama->title }} ({{ $kdrama->release_year }})</h2>--}}
{{--            <p>{{ $kdrama->description }}</p>--}}
{{--            <p><strong>Genre:</strong> {{ $kdrama->genre }}</p>--}}
{{--            <img src="{{ $kdrama->image_url }}" alt="{{ $kdrama->title }}" width="150">--}}
{{--        </li>--}}
{{--    @endforeach--}}
{{--</ul>--}}
{{--</body>--}}
{{--</html>--}}



{{--test--}}

{{--@foreach($kdramas as $kdrama)--}}
{{--    <h2>{{ $kdrama->title }}</h2>--}}

{{--    <ul>--}}
{{--        @foreach($kdrama->getAttributes() as $key => $value)--}}
{{--            @if($key === 'image_url')--}}
{{--                <!-- Laat een echte afbeelding zien -->--}}
{{--                <li>--}}
{{--                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong><br>--}}
{{--                    <img src="{{ $value }}" alt="{{ $kdrama->title }}" style="max-width:200px; height:auto;">--}}
{{--                </li>--}}
{{--            @else--}}
{{--                <!-- Laat de andere gegevens als tekst zien -->--}}
{{--                <li>--}}
{{--                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}--}}
{{--                </li>--}}
{{--            @endif--}}
{{--        @endforeach--}}
{{--    </ul>--}}

{{--    <hr>--}}
{{--@endforeach--}}
