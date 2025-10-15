<x-app-layout>
    <h1 class="text-white text-xl mb-4">Nieuwe Kdrama toevoegen</h1>

    <form action="{{ route('kdramas.store') }}" method="POST" class="text-white">
        @csrf <!-- voorkomt 419-fouten -->

        <div class="mb-4">
            <label for="title">Titel:</label><br>
            <input type="text" name="title" id="title" value="{{ old('title') }}"
                   style="background-color:#001F3F; color:white; padding:8px; border:none; border-radius:5px; width:100%;">
            @error('title')
            <p style="color:red;">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description">Beschrijving:</label><br>
            <textarea name="description" id="description"
                      style="background-color:#001F3F; color:white; padding:8px; border:none; border-radius:5px; width:100%;">{{ old('description') }}</textarea>
            @error('description')
            <p style="color:red;">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="genre">Genre:</label><br>
            <input type="text" name="genre" id="genre" value="{{ old('genre') }}"
                   style="background-color:#001F3F; color:white; padding:8px; border:none; border-radius:5px; width:100%;">
            @error('genre')
            <p style="color:red;">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="release_year">Releasejaar:</label><br>
            <input type="number" name="release_year" id="release_year" value="{{ old('release_year') }}"
                   style="background-color:#001F3F; color:white; padding:8px; border:none; border-radius:5px; width:100%;">
            @error('release_year')
            <p style="color:red;">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="image_url">Afbeeldings-URL:</label><br>
            <input type="text" name="image_url" id="image_url" value="{{ old('image_url') }}"
                   style="background-color:#001F3F; color:white; padding:8px; border:none; border-radius:5px; width:100%;">
            @error('image_url')
            <p style="color:red;">{{ $message }}</p>
            @enderror
        </div>



        <button type="submit"
                style="background-color:#003399; color:white; padding:8px 16px; border:none; border-radius:5px; cursor:pointer;">
            Opslaan
        </button>
    </form>
</x-app-layout>
