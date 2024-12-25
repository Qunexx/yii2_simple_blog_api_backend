<?php

namespace backend\services;
use backend\validators\RegisterForm;
use common\models\User;
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
        $existingUser = User::findOne(['email' => $validator->email]);
        if ($existingUser) {
            return [
                'success' => false,
                'errors' => [
                    'email' => ['Пользователь с таким email уже существует.'],
                ],
            ];
        }
        $user = new User();
        $user->name = $validator->name;
        $user->email = $validator->email;
        $user->setPassword($validator->password);
        $user->created_at = date('Y-m-d H:i:s');
        if ($user->save()) {
            return [
                'success' => true,
                'access_token' => '',
            ];
        } else {
            return [
                'success' => false,
                'errors' => $user->getErrors(),
            ];
        }
    }
}



