<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            // 'birthday' => ['string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();

        return User::create([
            'name' => $input['first_name'].' '.$input['last_name'],
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'birthday' => isset($input['birthday']) ? $input['birthday'] : null,
            'phone_number' => $input['phone_number'],
            'email' => $input['email'],
            'source' => isset($input['source']) ? $input['source'] : null,
            'campaign_id' => isset($input['campaign_id']) ? $input['campaign_id'] : null,
            'password' => Hash::make($input['password']),
        ]);
    }
}
