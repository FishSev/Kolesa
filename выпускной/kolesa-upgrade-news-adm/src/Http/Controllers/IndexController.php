<?php

namespace App\Http\Controllers;

use App\ChatService;
use App\ChatClient;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Slim\Views\Twig;

class IndexController
{
    function home(ServerRequest $request, Response $response)
    {
        $chatServiceClient = new ChatClient();
        $chat = new ChatService($chatServiceClient);
        $view = Twig::fromRequest($request);

        try {
            $isAvailable = $chat->checkHealth();
            if ($isAvailable === true) {
                return $view->render($response, 'home.twig');
            }
        } catch (\Exception $error) {
            return $view->render($response, "connectError.twig", ['error' => $error->getMessage()]);
        }
    }
}
