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

class Post extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%userPosts}}';
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

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }



    public function createPost(int $userId, string $text): array
    {
        $post = new Post();
        $post->user_id = $userId;
        $post->text = $text;
        $post->created_at = date('Y-m-d H:i:s');
        if ($post->save()) {
            return [
                'success' => true,
                'post' => $post,
            ];
        } else {
            return [
                'success' => false,
                'errors' => $this->getErrors(),
            ];
        }
    }

    public static function getUserPosts(int $userId, string $validatedLimit, string $validatedOffset): array
    {
        $posts = Post::find()
            ->where(['user_id' => $userId])
            ->limit($validatedLimit)
            ->offset($validatedOffset)
            ->all();

        return $posts;
    }

    public static function getPosts(string $validatedLimit, string $validatedOffset): array
    {
        $posts = Post::find()->limit($validatedLimit)
            ->offset($validatedOffset)
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $posts;
    }

}
