<?php

namespace app\Core;

use app\Core\Registry;
use Faker\Factory;

class FillDb
{
    //private static string $usernameUrl = 'https://potterapi-fedeperin.vercel.app/en/characters';

    private static function getResponse($url): bool|string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public static function connectToOpenApi(): array|false
    {
        try {
            $response = [];
            $arr = ['admin', 'user'];
            $users = json_decode(self::getResponse(Registry::getInstance()->get('fillUrl')), true);
            foreach ($users as $user) {
                $username = $user['fullName'];
                $faker = Factory::create();
                $email = $faker->email();
                $password = hash('sha256', $faker->password());
                $birthdate = $user['birthdate'];
                $role = $arr[array_rand($arr, 1)];

                $response[] = [
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'birthdate' => $birthdate,
                    'role' => $role
                ];
            }
            return $response;
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
}
