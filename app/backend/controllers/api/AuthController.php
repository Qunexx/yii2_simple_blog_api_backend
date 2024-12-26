<?php

namespace backend\controllers\api;

use backend\services\AuthService;
use backend\validators\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;


class AuthController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error','register'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                    'register'=> ['post'],
                    'login'=> ['post'],
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


    public function actionRegister(Request $request, AuthService $authService): Response
    {
        $result = $authService->registerUser($request->post());
        if ($result['success']) {
            return $this->asJson([
               'access_token' => $result['access_token']
            ]);
        } else {
            Yii::$app->response->statusCode = 400;
            return $this->asJson([
                'status' => 'error',
                'errors' => $result['errors'],
            ]);
        }

    }

    public function actionLogin(Request $request, AuthService $authService) : Response
    {
        $result = $authService->loginUser($request->post());
        if ($result['success']) {
            return $this->asJson([
                'access_token' => $result['access_token']
            ]);
        } else {
            Yii::$app->response->statusCode = 400;
            return $this->asJson([
                'status' => 'error',
                'errors' => $result['errors'],
            ]);
        }
    }
    public function actionLogout(Request $request, AuthService $authService) : Response
    {
        $authHeader = $request->headers->get('Authorization');
        if (!$authHeader) {
            return $this->asJson([
                'status' => 'error',
                'errors' => ['Заголовок Authorization отсутствует'],
            ]);
        }
        $result = $authService->logoutUser($authHeader);
        if($result['success']) {
            return $this->asJson([
                'status' => 'ok',
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
