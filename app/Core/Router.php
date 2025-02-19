<?php

namespace app\Core;

class Router
{
    public static function processRequest(): void
    {
        $request = new Request();
        $id = $request->id;
        $userId = $request->userId;
        $uri = $request->configRoute;
        $method = $request->getMethod();

        if (array_key_exists($uri, ROUTES)) {
            $matchMethod = false;
            foreach (ROUTES[$uri] as $route) {
                if ($route[0] == $method) {
                    $matchMethod = true;
                    $controller = $route[1][0];
                    $action = $route[1][1];
                    $controllerFullName = "app\\Controllers\\" . $controller;
                    $controllerPath = APP . '/Controllers/' . $controller . '.php';
                    if (file_exists($controllerPath)) {
                        $c = new $controllerFullName();
                        if (method_exists($c, $action)) {
                            if ($id != null) {
                                if ($userId != null) {
                                    $c->$action($id, $userId);
                                } else {
                                    $c->$action($id);
                                }
                            } else {
                                $c->$action();
                            }
                        } else {
                            dump('Controller action not found!');
                        }
                    } else {
                        dump('Controller class not found!');
                    }
                }
            }
            if (!$matchMethod) {
                self::ErrorPage(405);
            }
        } else {
            self::ErrorPage(404);
        }
    }

    public static function ErrorPage(int $status): void
    {
        http_response_code($status);
        header("HTTP/1.1 $status");
    }
}
