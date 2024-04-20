<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer;
use App\Repositories\UserRepository;

class Login
{
    public function __construct(private PhpRenderer $view,
                                private UserRepository $repository)
    {
    }

    public function new(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'login.php');
    }

    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $user = $this->repository->find('email', $data['email']);

        if ($user && password_verify($data['password'], $user['password_hash'])) {

            $_SESSION['user_id'] = $user['id'];

            return $response
                ->withHeader('Location', '/')
                ->withStatus(302);

        }

        return $this->view->render($response, 'login.php', [
            'data' => $data,
            'error' => 'Invalid login'
        ]);
    }

    public function destroy(Request $request, Response $response): Response
    {
        session_destroy();

        return $response
            ->withHeader('Location', '/')
            ->withStatus(302);
    }
}