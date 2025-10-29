{{-- Dit start de standaard Laravel-layout (meestal resources/views/layouts/app.blade.php) --}}
<x-app-layout>

    {{-- 'x-slot' plaatst deze HTML in de '$header' variabele van de layout --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gebruikersbeheer') }}
        </h2>
    </x-slot>

    {{-- 'py-12' is Tailwind voor verticale padding (boven/onder) --}}
    <div class="py-12">
        {{-- 'max-w-7xl' stelt de maximale breedte in, 'mx-auto' centreert het --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Dit is de witte 'kaart' met afgeronde hoeken en schaduw --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{-- 'p-6' geeft padding aan de binnenkant van de kaart --}}
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-medium mb-4">Alle Gebruikers</h3>

                    {{-- Start van de tabel --}}
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50"> {{-- De kop van de tabel (donkere achtergrond) --}}
                        <tr>
                            {{-- Kolomkoppen --}}
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Naam</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">E-mail</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actie</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        {{-- Start de Blade-loop. De '$users' variabele komt uit de UserController --}}
                        @foreach ($users as $user)
                            <tr>
                                {{-- Cel 1: Naam --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $user->name }}

                                    {{-- Extra check: Toon een 'Admin' badge als de role_id 1 is --}}
                                    @if ($user->role_id == 1)
                                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Admin
                                            </span>
                                    @endif
                                </td>

                                {{-- Cel 2: E-mail --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->email }}
                                </td>

                                {{-- Cel 3: Status --}}
                                {{-- We geven deze <td> een UNIEK ID (bijv. "status-text-5") --}}
                                {{-- Dit ID wordt door het Ajax-script gebruikt om de tekst te kunnen bijwerken --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm" id="status-text-{{ $user->id }}">
                                    {{-- Controleer de 'is_active' boolean (1 of 0) en toon de juiste badge --}}
                                    @if ($user->is_active)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Actief
                                            </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Niet Actief
                                            </span>
                                    @endif
                                </td>

                                {{-- Cel 4: Actieknop --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">

                                    {{-- Veiligheidscheck: Toon de knop NIET als de user-ID gelijk is aan de ID van de ingelogde admin --}}
                                    {{-- (auth()->id() is de ID van de ingelogde gebruiker) --}}
                                    @if ($user->id !== auth()->id())

                                        {{-- Dit is een mini-formulier dat de POST-call maakt --}}
                                        {{-- We geven het de class 'toggle-form' zodat ons script het kan vinden --}}
                                        <form action="{{ route('users.toggleStatus', $user) }}" method="POST" class="toggle-form">
                                            @csrf {{-- Verplicht Laravel token voor POST-requests --}}

                                            {{-- Bepaal de tekst en kleur van de knop op basis van de huidige status --}}
                                            @if ($user->is_active)
                                                {{-- Deze knop krijgt ook een UNIEK ID voor het script (bijv. "toggle-button-5") --}}
                                                <button type="submit" id="toggle-button-{{ $user->id }}" class="text-red-600 hover:text-red-900">
                                                    Zet op Niet Actief
                                                </button>
                                            @else
                                                <button type="submit" id="toggle-button-{{ $user->id }}" class="text-green-600 hover:text-green-900">
                                                    Zet op Actief
                                                </button>
                                            @endif
                                        </form>
                                    @else
                                        {{-- Als het de ingelogde admin zelf is, toon "(Jij)" --}}
                                        <span class="text-gray-400">(Jij)</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach {{-- Einde van de @foreach loop --}}
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    {{-- HET AJAX SCRIPT --}}
    {{-- @push('scripts') zorgt ervoor dat dit script onderaan de <body> wordt geladen --}}
    {{-- (Dit vereist dat je layout @stack('scripts') heeft) --}}
    @push('scripts')
        <script>
            // Wacht tot de volledige HTML-pagina is geladen
            document.addEventListener('DOMContentLoaded', function() {

                // Selecteer ALLE formulieren met de class 'toggle-form'
                document.querySelectorAll('.toggle-form').forEach(form => {

                    // Voeg een 'luisteraar' toe aan elk van die formulieren voor het 'submit' event
                    form.addEventListener('submit', function (event) {

                        // 1. Voorkom het standaard gedrag van het formulier (dat de pagina herlaadt)
                        event.preventDefault();

                        // Sla de data uit het formulier op
                        const url = this.action; // De URL (bijv. /users/5/toggle-status)
                        const token = this.querySelector('input[name="_token"]').value; // Het CSRF-token

                        // 2. Stuur het netwerkverzoek (Fetch API)
                        fetch(url, {
                            method: 'POST', // Het MOET een POST zijn (zoals in web.php)
                            headers: {
                                'X-CSRF-TOKEN': token, // Stuur het token mee in de header
                                'Accept': 'application/json', // Zeg tegen Laravel: "Ik verwacht JSON terug"
                                'Content-Type': 'application/json',
                            }
                        })
                            .then(response => {
                                // 3. EERSTE .then() - Controleer de HTTP-status van de server

                                // Check of de server-response 'OK' was (status 200)
                                if (!response.ok) {
                                    // Zo nee (bijv. 403, 404, 500), lees de JSON-foutmelding die de controller stuurde
                                    return response.json().then(errorData => {
                                        // Gooi een nieuwe Error. Dit stopt de '.then()' keten
                                        // en gaat direct naar de '.catch()' hieronder.
                                        throw new Error(errorData.message || 'Serverfout: ' + response.status);
                                    });
                                }
                                // Zo ja, geef de JSON data door aan de volgende '.then()'
                                return response.json();
                            })
                            .then(data => {
                                // 4. TWEEDE .then() - Verwerk de succesvolle data

                                // 'data' bevat nu het object dat de controller stuurde (success: true, new_text, etc.)
                                if (data.success) {

                                    // Haal het unieke ID van de gebruiker uit de URL
                                    const userId = url.split('/')[4];

                                    // Selecteer de HTML-elementen die we moeten bijwerken
                                    const statusTextElement = document.getElementById('status-text-' + userId);
                                    const buttonElement = document.getElementById('toggle-button-' + userId);

                                    // Update de status-tekst (met de juiste HTML & Tailwind classes)
                                    statusTextElement.innerHTML = data.is_active
                                        ? `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Actief</span>`
                                        : `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Niet Actief</span>`;

                                    // Update de knop-tekst en de kleur-class
                                    buttonElement.textContent = data.new_button_text;
                                    if (data.is_active) {
                                        buttonElement.className = 'text-red-600 hover:text-red-900'; // Status is nu Actief -> knop wordt "zet uit" (rood)
                                    } else {
                                        buttonElement.className = 'text-green-600 hover:text-green-900'; // Status is nu Inactief -> knop wordt "zet aan" (groen)
                                    }

                                }
                            })
                            .catch(error => {
                                // 5. .catch() - Vang alle fouten af

                                // Dit vangt netwerkfouten op, maar ook de 'Error' die we hierboven
                                // handmatig gooiden (bijv. "Je kunt je eigen account niet deactiveren.")
                                console.error('Error:', error);
                                alert('Fout: ' + error.message);
                            });
                    });
                });
            });
        </script>
    @endpush

</x-app-layout>
