<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;


class AccessToken extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%accessTokens}}';
    }


    public function behaviors()
    {
        return [
        ];
    }


    public function rules()
    {
        return [
        ];
    }


    public  function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    public static function generateAccessToken(int $userId): string
    {
        do {
            $token = Yii::$app->security->generateRandomString() ;
        } while (self::findOne(['token' => $token]));

        $accessToken = new AccessToken();
        $accessToken->user_id = $userId;
        $accessToken->token = $token;
        $accessToken->created_at = date('Y-m-d H:i:s');

        if ($accessToken->save()) {
            return $token;
        } else {
            return false;
        }
    }


}
