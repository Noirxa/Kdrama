<?php

namespace App\Http\Requests\Auth;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

// --- HIER TOEGEVOEGD ---
use App\Models\User; // Nodig om de gebruiker handmatig op te zoeken
use Illuminate\Support\Facades\Hash; // Nodig om het wachtwoord handmatig te checken
// --- EINDE TOEVOEGING ---

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // --- HET ORIGINELE 'Auth::attempt' BLOK IS VERVANGEN DOOR DIT: ---

        // 1. Haal de 'email' en 'password' uit het formulier
        $credentials = $this->only('email', 'password');

        // 2. Zoek de gebruiker in de database op basis van e-mail
        $user = User::where('email', $credentials['email'])->first();

        // 3. Controleer of de gebruiker bestaat ÉN of het wachtwoord klopt
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {

            // Als het niet klopt, geef de standaard "mislukt" melding
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'), // "These credentials do not match..."
            ]);
        }

        // 4. *** DE NIEUWE CHECK ***
        //    Als de gebruiker wél klopt, controleer nu of 'is_active' true (1) is
        if (! $user->is_active) {

            // Zo nee, geef je EIGEN specifieke melding terug!
            RateLimiter::hit($this->throttleKey()); // Telt ook als mislukte poging
            throw ValidationException::withMessages([
                'email' => 'Dit account is gedeactiveerd of verbannen.',
            ]);
        }

        // 5. Alle checks zijn geslaagd. Log de gebruiker in.
        Auth::login($user, $this->boolean('remember'));

        // --- EINDE VAN HET VERVANGEN BLOK ---

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
