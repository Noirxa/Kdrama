<!-- resources/views/reviews/edit.blade.php -->

<!-- Deze view toont een formulier om een bestaande review te bewerken. -->
<!-- Het hoort bij de functie 'edit' in ReviewController. -->

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Review bewerken</title>
    <!-- Bootstrap-styling (optioneel, voor mooiere knoppen en velden) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h1 class="mb-4">Review bewerken</h1>

    <!-- Terugknop -->
    <a href="{{ route('kdramas.show', $review->kdrama_id) }}" class="btn btn-secondary mb-3">← Terug naar K-Drama</a>

    <!-- Toon validatiefouten als de gebruiker iets verkeerd invoert -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Oeps!</strong> Er ging iets mis met je invoer:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulier om de review te updaten -->
    <!-- De @method('PUT') is nodig omdat HTML geen PUT ondersteunt -->
    <form action="{{ route('reviews.update', $review->id) }}" method="POST" class="card p-4 shadow-sm bg-white">
        @csrf
        @method('PUT')

        <!-- Rating veld -->
        <div class="mb-3">
            <label for="rating" class="form-label">Beoordeling (rating)</label>
            <input type="text" name="rating" id="rating" class="form-control"
                   value="{{ old('rating', $review->rating) }}" required>
            <small class="text-muted">Bijv: "5 sterren" of "Goed".</small>
        </div>

        <!-- Comment veld -->
        <div class="mb-3">
            <label for="comment" class="form-label">Opmerking (comment)</label>
            <textarea name="comment" id="comment" class="form-control" rows="4" required>{{ old('comment', $review->comment) }}</textarea>
        </div>

        <!-- Opslaan knop -->
        <button type="submit" class="btn btn-primary">Wijzigingen opslaan</button>
    </form>
</div>

</body>
</html>
