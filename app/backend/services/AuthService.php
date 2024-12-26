<?php

namespace backend\services;
use backend\validators\LoginForm;
use backend\validators\RegisterForm;
use common\models\User;
use Yii;
use yii\web\Request;

class AuthService
{
    public function registerUser(array $request): array
    {
        $validator = new RegisterForm();
        $validator->load($request, '');
        if (!$validator->validate()) {
            return [
                'success' => false,
                'errors' => $validator->getErrors(),
            ];
        }
        $existingUser = User::findUserByEmail($validator->email);
        if ($existingUser) {
            return [
                'success' => false,
                'errors' => [
                    'email' => ['Пользователь с таким email уже существует.'],
                ],
            ];
        }
        $user = new User();
        $result = $user->createUser($validator);
        if ($result['success']) {
            return [
                'success' => true,
                'access_token' => $result['access_token'],
            ];
        } else {
            return [
                'success' => false,
                'errors' => $result['errors'],
            ];
        }
    }

    public function loginUser(array $request): array
    {
        $validator = new LoginForm();
        $validator->load($request, '');
        if (!$validator->validate()) {
            return [
                'success' => false,
                'errors' => $validator->getErrors(),
            ];
        }
        $login = User::login($validator);
        if (!$login['success']) {
            return [
                'success' => false,
                'errors' => ['Неверная почта или пароль'],
            ];
        }

       return [
        'success' => true,
        'access_token' => $login['access_token'],
        ];
    }

    public function logoutUser($authorization_header): array
    {
        $accessToken = str_replace('Bearer ', '', $authorization_header);
        $logout = User::logout($accessToken);
        if (!$logout['success']) {
            return [
                'success' => false,
                'errors' => $logout['errors'],
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function checkAuth($authHeader): array | User
    {
        if (!$authHeader) {
        Yii::$app->response->statusCode = 400;
        return [
            'status' => 'error',
            'errors' => ['Заголовок Authorization отсутствует'],
        ];
        }
        $accessToken = str_replace('Bearer ', '', $authHeader);
        $user = User::findIdentityByAccessToken($accessToken);
        if ($user) {
            return $user;
        } else {
            return [
                'status' => 'error',
                'errors' => ['Недействительный Authorization token'],
            ];
        }

    }
}



