<?php

namespace backend\controllers\api;

use backend\services\AuthService;
use backend\services\BlogService;
use backend\validators\createPostForm;
use backend\validators\LoginForm;
use common\models\AccessToken;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;


class BlogController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create-post' => ['post'],
                    'get-my-posts' => ['get'],
                    'get-posts' => ['get'],
                ],
            ],
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }


    public function actionCreatePost(Request $request, BlogService $blogService, AuthService $authService): Response
    {
        $authHeader = $request->headers->get('Authorization');
        $user = $authService->checkAuth($authHeader);
        if ($user['errors']) {
            Yii::$app->response->statusCode = 401;
            return $this->asJson([
                'status' => 'error',
                'errors' => $user['errors'],
            ]);
        }

        $result = $blogService->createPost($user['id'], $request->post());
        if ($result['success']) {
            return $this->asJson([
                'status' => 'success',
                'post' => $result['posts'],
            ]);
        } else {
            Yii::$app->response->statusCode = 400;
            return $this->asJson([
                'status' => 'error',
                'errors' => $result['errors'],
            ]);
        }
    }

    public function actionGetPosts(Request $request, AuthService $authService, BlogService $blogService): Response
    {
        $result = $blogService->getPosts($request->get());

        if ($result['success']) {
            return $this->asJson([
                'status' => 'success',
                'posts' => $result['posts'],
            ]);
        } else {
            Yii::$app->response->statusCode = 400;
            return $this->asJson([
                'status' => 'error',
                'errors' => $result['errors'],
            ]);
        }

    }

    public function actionGetMyPosts(Request $request, AuthService $authService, BlogService $blogService): Response
    {
        $authHeader = $request->headers->get('Authorization');
        $user = $authService->checkAuth($authHeader);
        if ($user['errors']) {
            Yii::$app->response->statusCode = 401;
            return $this->asJson([
                'status' => 'error',
                'errors' => $user['errors'],
            ]);
        }

        $result = $blogService->getMyPosts($user['id'], $request->get());

        if ($result['success']) {
            return $this->asJson([
                'status' => 'success',
                'posts' => $result['posts'],
            ]);
        } else {
            Yii::$app->response->statusCode = 400;
            return $this->asJson([
                'status' => 'error',
                'errors' => $result['errors'],
            ]);
        }

    }


}
