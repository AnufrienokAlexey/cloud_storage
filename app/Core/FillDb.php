<?php

namespace app\Core;

class FillDb
{
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
            $arr = [];
            $url = 'https://potterapi-fedeperin.vercel.app/en/characters';
            $response = self::getResponse($url);
            $result = json_decode($response, true);
            dump($result);
            if (isset($result)) {
                foreach ($result as $character) {
                    $arr[] = $character['fullName'];
                }
                return $arr;
            } else {
                die('Подключение к стороннему open Api не произошло!');
            }
        } catch (\Exception $e) {
            dump($e->getMessage());
        }
        return false;
    }
}
