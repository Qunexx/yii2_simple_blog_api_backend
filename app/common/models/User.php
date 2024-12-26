<?php

namespace common\models;

use backend\validators\LoginForm;
use backend\validators\RegisterForm;
use common\models\AccessToken;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $name
 * @property string $password_hash
 * @property string $email
 * @property integer $auth_key
 * @property integer $is_admin
 * @property integer $created_at
 */
class User extends ActiveRecord implements IdentityInterface
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
        ];
    }

    public function getAccessTokens()
    {
        return $this->hasMany(AccessToken::class, ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $accessToken = AccessToken::find()
            ->where(['token' => $token])
            ->one();

        if ($accessToken) {
            $user = User::find()
                ->where(['id' => $accessToken->user_id])
                ->one();

            return $user;
        }

        return null;
    }


    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function createUser(RegisterForm $validator): array
    {
        $user = new User();
        $user->name = $validator->name;
        $user->email = $validator->email;
        $user->setPassword($validator->password);
        $user->created_at = date('Y-m-d H:i:s');
        if ($user->save()) {
            $accessToken = new AccessToken();
            $token = $accessToken->generateAccessToken($user->id);
            return [
                'success' => true,
                'user' => $user,
                'access_token' => $token,
            ];
        } else {
            return [
                'success' => false,
                'errors' => $user->getErrors(),
            ];
        }
    }

    public static function findUserByEmail(string $email) : User | bool
    {
        $existingUser = User::findOne(['email' => $email]);
        return $existingUser ?: false;
    }

    public static function login(LoginForm $validator): array
    {
        $user = User::findUserByEmail($validator->email);
        if (!$user || !$user->validatePassword($validator->password)) {
            return [
                'success' => false,
                'errors' => ['Неверный логин или пароль'],
            ];
        }
        $accessToken = new AccessToken();
        $token = $accessToken->generateAccessToken($user->id);
        return [
            'success' => true,
            'access_token' => $token,
        ];
    }

    public static function logout(string $access_token): array
    {
        $forget_acccess_token = AccessToken::forgetAccessToken($access_token);
        if($forget_acccess_token['success']){
            return [
                'success' => true,
                'message' => $forget_acccess_token['message'],
            ];
        } else {
            return [
                'success' => false,
                'errors' => $forget_acccess_token['errors'],
            ];
        }

    }
}
