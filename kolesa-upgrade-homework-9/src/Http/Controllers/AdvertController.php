<?php

namespace App\Http\Controllers;

use App\Model\Repository\AdvertRepositoryMySQL;
use App\Model\Validators\AdvertValidator;
use Slim\Http\ServerRequest;
use Slim\Http\Response;
use Slim\Views\Twig;

class AdvertController
{
    public function index(ServerRequest $request, Response $response)
    {
        $repo = new AdvertRepositoryMySQL();
        $adverts     = $repo->getAll();
        $view = Twig::fromRequest($request);
        return $view->render($response, 'adverts/index.twig', ['adverts' => $adverts]);
    }

    public function newAdvert(ServerRequest $request, Response $response)
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'adverts/new.twig');
    }

    public function create(ServerRequest $request, Response $response)
    {
        $repo        = new AdvertRepositoryMySQL();
        $advertData  = $request->getParsedBodyParam('advert', []);
        $validator = new AdvertValidator();
        $errors    = $validator->validate($advertData);
        if (!empty($errors)) {
            $view = Twig::fromRequest($request);
            return $view->render($response, 'adverts/new.twig', [
                'data'   => $advertData,
                'errors' => $errors,
            ]);
        }
        $repo->create($advertData);
        return $response->withRedirect('/adverts');
    }

    public function getAdvertByID(ServerRequest $request, Response $response, $id)
    {
        $repo = new AdvertRepositoryMySQL();
        $advertData = $repo->getAdvertByID($id['id']);
        $view = Twig::fromRequest($request);
        return $view->render($response, 'adverts/advertID.twig', ['advert' => $advertData]);
    }

    public function editAdvertGet(ServerRequest $request, Response $response, $id)
    {
        $repo = new AdvertRepositoryMySQL();
        $advertData = $repo->getAdvertByID($id['id']);
        $view = Twig::fromRequest($request);
        return $view->render($response, 'adverts/editAdvert.twig', ['data' => $advertData, 'id' => $id['id']]);
    }

    public function editAdvertPost(ServerRequest $request, Response $response, $id)
    {
        $repo        = new AdvertRepositoryMySQL();
        $advertData  = $request->getParsedBodyParam('advert', []);
        $validator = new AdvertValidator();
        $errors    = $validator->validate($advertData);
        if (!empty($errors)) {
            $view = Twig::fromRequest($request);
            return $view->render($response, 'adverts/editAdvert.twig', [
                'data'   => $advertData,
                'id' => $id['id'],
                'errors' => $errors,
            ]);
        }
        $repo->editAdvert($advertData, $id['id']);
        return $response->withRedirect('/adverts/' . $id['id']);
    }
}
