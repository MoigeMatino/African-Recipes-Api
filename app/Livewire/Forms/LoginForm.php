<?php

namespace App\Livewire\Forms;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|string|min:2')]
    public string $emailOrUsername = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function attemptAuth(array $fields): void
    {
        if (! Auth::attempt($fields, $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.emailOrUsername' => trans('auth.failed'),
            ]);
        }
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (preg_match("/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/", $this->emailOrUsername)) {
            $this->attemptAuth(['email' => $this->emailOrUsername, 'password' => $this->password]);
        } else {
            $this->attemptAuth(['username' => $this->emailOrUsername, 'password' => $this->password]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.emailOrUsername' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->emailOrUsername).'|'.request()->ip());
    }
}
