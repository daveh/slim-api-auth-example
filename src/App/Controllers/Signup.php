<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer;
use Valitron\Validator;
use App\Repositories\UserRepository;
use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto;

class Signup
{
    public function __construct(private PhpRenderer $view,
                                private Validator $validator,
                                private UserRepository $repository)
    {
        $this->validator->mapFieldsRules([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', ['lengthMin', 6]],
            'password_confirmation' => ['required', ['equals', 'password']]
        ]);

        $this->validator->rule(function ($field, $value, $params, $fields) {

            return $this->repository->find('email', $value) === false;

        }, 'email')->message('{field} is already taken');
    }

    public function new(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'signup.php');
    }

    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $this->validator = $this->validator->withData($data);

        if ( ! $this->validator->validate()) {

            return $this->view->render($response, 'signup.php', [
                'errors' => $this->validator->errors(),
                'data' => $data
            ]);

        }

        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $api_key = bin2hex(random_bytes(16));

        $encryption_key = Key::loadFromAsciiSafeString($_ENV['ENCRYPTION_KEY']);

        $data['api_key'] = Crypto::encrypt($api_key, $encryption_key);

        $data['api_key_hash'] = hash_hmac('sha256', $api_key, $_ENV['HASH_SECRET_KEY']);

        $this->repository->create($data);

        return $response
            ->withHeader('Location', '/signup/success')
            ->withStatus(302);
    }

    public function success(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'signup-success.php');
    }
}