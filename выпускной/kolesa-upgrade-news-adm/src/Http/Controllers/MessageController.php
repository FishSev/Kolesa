<?php

namespace App\Http\Controllers;

use Slim\Http\ServerRequest;
use Slim\Http\Response;
use SLim\Views\Twig;
use App\Model\Validators\MessageValidator;
use App\Repository\MessageRepository;
use App\ChatService;
use App\ChatClient;

class MessageController
{
    public function new(ServerRequest $request, Response $response)
    {
        $view = Twig::fromRequest($request);

        return $view->render($response, 'Message/index.twig');
    }

    public function create(ServerRequest $request, Response $response)
    {
        $view = Twig::fromRequest($request);
        $messageData  = $request->getParsedBodyParam('message', []);
        $validator = new MessageValidator();
        $errors    = $validator->validate($messageData);

        if (!empty($errors)) {
            $view = Twig::fromRequest($request);

            return $view->render($response, 'Message/index.twig', [
                'data'   => $messageData,
                'errors' => $errors,
            ]);
        }

        $chatServiceClient = new ChatClient();
        $chat = new ChatService($chatServiceClient);
        try {
            $chat->sendMessage($messageData);
        } catch (\Exception $exception) {
            return $view->render($response, 'sendError.twig', ['error' => $exception->getMessage()]);
        };

        $repo = new MessageRepository();
        $repo->create($messageData);
        return $view->render($response, 'Message/index.twig', ['messages' => [$messageData]]);
    }

    public function allMessages(ServerRequest $request, Response $response)
    {
        $repo = new MessageRepository();
        $messages = $repo->getAll();

        $view = Twig::fromRequest($request);

        return $view->render($response, 'Message/listOfMsg.twig', ["messages" => $messages]);
    }
}
