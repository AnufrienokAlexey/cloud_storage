<?php

namespace app\core;

class FillDb
{


    public static function getRequest($url): bool|string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public static function connectToOpenApi(): void
    {
        try {
            $url = 'https://potterapi-fedeperin.vercel.app/en/characters';
            $response = self::getRequest($url);
            $result = json_decode($response, true);
            if (isset($result)) {
                foreach ($result as $character) {
                    dump($character['fullName']);
                }
            } else {
                dump('Подключение к стороннему open Api не произошло!');
            }
            self::fillDb();
        } catch (\Exception $e) {
            dump($e->getMessage());
        }
    }

    public static function fillDb(): void
    {
//        $db = Connect::connect();
//        dump($db);
//        try {
//            $stm = $db->prepare("SELECT * FROM users");
//        } catch (\Exception $e) {
//            dump($e->getMessage());
//        }
        echo "fillDb()";
    }
}
