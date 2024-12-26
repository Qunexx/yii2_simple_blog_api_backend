<?php

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Добро пожаловать в админку!</h1>

    </div>
    <p class="text-center">
        <a class="btn btn-primary btn-lg" href="<?= Yii::$app->urlManager->createUrl(['/admin/user/index']) ?>">Управление пользователями</a>
        <a class="btn btn-success btn-lg" href="<?= Yii::$app->urlManager->createUrl(['/admin/post/index']) ?>">Управление постами</a>
    </p>

</div>
