<?php

namespace backend\validators;

use common\models\User;
use Yii;
use yii\base\Model;

class LoginForm extends Model
{

    public $email;
    public $password;
    private $_user;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
        ];
    }


    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            if ($user && ($user->is_admin === 1)) {
                return Yii::$app->user->login($user);
            } else {
                $this->addError('email', 'Доступ разрешен только администраторам или вы ввели неверные данные');
            }
        }
        return false;
    }
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findUserByEmail($this->email);
        }
        return $this->_user;
    }

}
