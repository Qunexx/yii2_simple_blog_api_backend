<?php

namespace backend\services;
use backend\validators\createPostForm;
use backend\validators\getMyPostsForm;
use backend\validators\getPostsForm;
use backend\validators\LoginForm;
use backend\validators\RegisterForm;
use common\models\Post;
use common\models\User;
use yii\web\Request;

class BlogService
{
    public function getMyPosts(int $userId, array $request): array
    {
        $validator = new getMyPostsForm();
        $validator->load($request,'');
        if (!$validator->validate()) {
            return [
                'success' => false,
                'errors' => $validator->getErrors(),
            ];
        }

        $posts = Post::getUserPosts($userId, $validator->limit, $validator->offset);

        return [
            'success' => true,
            'posts' => $posts,
        ];
    }

    public function createPost(int $userId, array $request): array
    {
        $validator = new createPostForm();
        $validator->load($request, '');
        if (!$validator->validate()) {
            return [
                'success' => false,
                'errors' => $validator->getErrors(),
            ];
        }
         $post = new Post();
         $post = $post->createPost($userId, $validator->text);
         if($post['success']){
             return [
                 'success' => true,
                 'posts' => $post['post'],
             ];

         } else {
             return [
                 'success' => true,
                 'errors' => $post['errors'],
             ];
         }

    }

    public function getPosts(array $request): array
    {
        $validator = new getPostsForm();
        $validator->load($request,'');
        if (!$validator->validate()) {
            return [
                'success' => false,
                'errors' => $validator->getErrors(),
            ];
        }

        $posts = Post::getPosts($validator->limit, $validator->offset);

        return [
            'success' => true,
            'posts' => $posts,
        ];
    }
}



