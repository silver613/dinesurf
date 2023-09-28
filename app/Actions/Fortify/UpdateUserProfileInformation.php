<?php

namespace App\Actions\Fortify;

use App\Services\AllServices\FileService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {

        // dd($input);
        Validator::make($input, [
            'username' => ['required', 'string', 'max:255'],
            'displayname' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'bio' => ['nullable', 'string'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            // $user->updateProfilePhoto($input['photo']);
            $path = FileService::storeFile($input['photo'], 'avatars', true);
            $user->image = $path;
            $user->save();
        }

        if (
            $input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail
        ) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'displayname' => $input['displayname'],
                'username' => $input['username'],
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'birthday' => $input['birthday'],
                'phone_number' => $input['phone_number'],
                'email' => $input['email'],
                'name' => $input['first_name'].' '.$input['last_name'],
                'bio' => $input['bio'],
                'instal_link' => $input['insta_link'],
                'twitter_link' => $input['twitter_link'],
                'tiktok_link' => $input['tiktok_link'],

            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function updateVerifiedUser($user, array $input)
    {
        $user->forceFill([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'birthday' => $input['birthday'],
            'phone_number' => $input['phone_number'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
