<?php

namespace app\Core;

class FillDb
{
    private static string $usernameUrl = 'https://potterapi-fedeperin.vercel.app/en/characters';
    private static string $randomDataUrl = 'https://random-data-api.com/api/v3/projects/31b583f2-351d-465f-b375-de15cbd07720';
    private static string $randomApiKey = 'C9cpzvOi937DrLEke0y85Q';

    private static function getResponse($url): bool|string
    {
        $headers = ['X-API-Key: C9cpzvOi937DrLEke0y85Q'];
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
