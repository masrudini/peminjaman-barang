<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class ReCaptcha implements ValidationRule
{
   
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Make a request to the Google reCAPTCHA API for verification
        $response = Http::asForm()->post("https://www.google.com/recaptcha/api/siteverify", [
            'secret' => "6LcBxGMqAAAAAJTQOIJQDwIWhfh0eITidKo9DXxu",
            'response' => $value
        ]);

        // Parse the response to check if the reCAPTCHA was successful
        if (!($response->json()['success'] ?? false)) {
            // Fail the validation with a message
            $fail('Google reCAPTCHA validation failed. Please try again.');
        }
    }
}
