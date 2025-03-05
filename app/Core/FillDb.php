<?php

namespace app\Core;

class FillDb
{
    private static string $usernameUrl = 'https://potterapi-fedeperin.vercel.app/en/characters';
    private static string $randomDataUrl = 'https://random-data-api.com/api/v3/projects/d7da5248-1a22-45cc-b051-58b5bdc04385';
    private static string $randomApiKey = 'C9cpzvOi937DrLEke0y85Q';

    private static function getResponse($url): bool|string
    {
        $headers = ['X-API-Key: Tvv_gsSIolV9UHg_EvRlYg'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
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
            $users = json_decode(self::getResponse(self::$usernameUrl), true);
            foreach ($users as $user) {
                $username = $user['fullName'];
                $randomData = json_decode(self::getResponse(self::$randomDataUrl), true);
                $email = $randomData['email'];
                $password = $randomData['password'];
                $speciality = $randomData['speciality'];
                $techSkill = $randomData['tech_skill'];
                $role = $arr[array_rand($arr, 1)];

                $response[] = [
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'speciality' => $speciality,
                    'tech_skill' => $techSkill,
                    'role' => $role
                ];
            }
            return $response;
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
}
