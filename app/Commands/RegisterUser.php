<?php

/**
 * https://cpitech.io
 */

namespace App\Commands;

use App\Model\User;
use Illuminate\Support\Facades\Hash;

/**
 * Class RegisterUser
 * @package App\Commands
 */
class RegisterUser
{

    /**
     * @param string $name
     * @param string $lastname
     * @param string $email
     * @param string $password
     * @param string $role
     * @return User
     */
    public function run(
        string $name,
        string $lastname,
        string $email,
        string $password,
        string $phone,
        string $street,
        string $houseno,
        string $zip,
        string $city,
        string $role = null
    ): User
    {
        \Log::debug(sprintf('Creating new user with email %s.', $email));


        $fullname = trim($name . $lastname);
        if (empty($fullname)) {
            throw new \InvalidArgumentException('Username cannot be empty.');
        }

        if (!$this->isValidName($fullname)) {
            throw new \InvalidArgumentException('Invalid user name');
        }

        $email = trim($email);

        if (empty($email)) {
            throw new \InvalidArgumentException('Email cannot be empty');
        }

        if (empty($password)) {
            throw new \InvalidArgumentException('Password cannot be empty');
        }

        $user = new User();
        $user->name = $name;
        $user->lastname = $lastname;
        $user->email = $email;
        $user->role = $role;
        $user->phone = $phone;
        $user->houseno = $houseno;
        $user->street = $street;
        $user->city = $city;
        $user->zip = $zip;
        $user->password = Hash::make($password);

        \Log::debug(
            sprintf(
                'User created with email %s and id %s.',
                $user->email,
                $user->id
            )
        );

        // Autoverify user - no verification mail needed!
        $user->email_verified_at = now();
        $user->save();


        return $user;
    }

    /**
     * @param $username
     * @return bool
     */
    private function isValidName($username): bool
    {
        $tokens = ['Admin', 'Manager', 'xxx'];
        foreach ($tokens as $token) {
            if (false !== stripos($username, $token)) {
                return false;
            }
        }

        return true;
    }
}
