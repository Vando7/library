<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => 'Library',
        'brandUrl' => null,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            Yii::$app->user->isGuest ? '' : (['label' => 'Books', 'url' => ['/book/index']]),
            Yii::$app->user->isGuest ? '' : ( Yii::$app->user->identity->role == 'reader' ? '' : (['label' => 'Users', 'url' => ['/user/index']])),
            Yii::$app->user->isGuest ? '' : ( Yii::$app->user->identity->role == 'reader' ? '' : (['label' => 'History - TODO...', 'url' => NULL])),
            
            // Pages viewable for all users
            Yii::$app->user->isGuest ? '' : (['label' => 'My Account', 'url' => ['/user/view?id='.Yii::$app->user->identity->id]]),
            Yii::$app->user->isGuest ? '' : (['label' => 'My History - TODO...', 'url' => NULL]),
            Yii::$app->user->isGuest ? '' : (['label' => 'My Books - TODO...', 'url' => NULL]),
            
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/user/login']]
                ) : (
                    '<li>'
                    . Html::beginForm(['/user/logout'], 'post', ['class' => 'form-inline'])
                    . Html::submitButton(
                        'Logout (' . Yii::$app->user->identity->first_name . ')',
                        ['class' => 'btn btn-link logout']
                        )
                        . Html::endForm()
                        . '</li>'
                    ),       
            Yii::$app->user->isGuest ? (
                ['label' => 'Signup', 'url' => ['/user/signup']]
            ) : '',
        ],
    ]);
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-left">&copy; My Company <?= date('Y') ?></p>
        <p class="float-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
