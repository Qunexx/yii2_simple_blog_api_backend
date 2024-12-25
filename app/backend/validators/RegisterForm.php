<?php

namespace backend\validators;

use yii\base\Model;

class RegisterForm extends Model
{
    public $name;
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['name', 'email', 'password'], 'required'],
            ['name', 'string', 'min' => 3, 'max' => 50],
            ['email', 'email'],
            ['password', 'string', 'min' => 6],
        ];
    }

}
