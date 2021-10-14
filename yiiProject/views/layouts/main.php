<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\VarDumper;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
            Launch demo modal
        </button>

        <!-- Modal -->


        <?php
        NavBar::begin([
            'brandLabel' => 'Library',
            'brandUrl' => null,
            'options' => [
                'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
            ],
        ]);

        $session = Yii::$app->session;

        if ($session->has('cart')) {
            $cart = $session['cart'];
            $cancelCart   = (['label' => 'Cancel 🛒', 'url' => '/book/clearcart']);

            $modalButton  = '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cartModal">';
            if ($cart['book'] !== []) {
                $modalButton .= '<i class="bi bi-cart-fill"></i> ';
            } else {
                $modalButton .= '<i class="bi bi-cart"></i> ';
            }
            $modalButton .= $cart['user']['name'];
            $modalButton .= ' <span class="badge badge-info">' . count($cart['book']) . '</span>';
            $modalButton .= '</button>';

            $cart = $session['cart'];
            if (empty($cart['book']) == false) {
                $clearCart  = (['label' => 'Clear 🛒',  'url' => '/book/clearcartitems']);
            } else {
                $clearCart = '';
            }
        } else {
            $modalButton = '';
            $cancelCart = '';
            $clearCart  = '';
        }

        echo Nav::widget([
            'options' => [
                'class' => 'navbar-nav'
            ],
            'items' => [
                Yii::$app->user->isGuest ? '' : (['label' => 'Books', 'url' => ['/book/index']]),
                Yii::$app->user->isGuest ? '' : (Yii::$app->user->identity->role == 'reader' ? '' : (['label' => 'Users', 'url' => ['/user/index']])),
                Yii::$app->user->isGuest ? '' : (Yii::$app->user->identity->role == 'reader' ? '' : (['label' => 'Problem Readers', 'url' => ['/user/problemreaders']])),
                Yii::$app->user->isGuest ? '' : (Yii::$app->user->identity->role == 'reader' ? '' : (['label' => 'History', 'url' => '/user/lendhistory'])),
                Yii::$app->user->isGuest ? '' : (Yii::$app->user->identity->role == 'reader' ? '' : (['label' => 'Reserved', 'url' => ['/user/reserved']])),

                // Pages viewable for all users
                Yii::$app->user->isGuest ? '' : (['label' => 'My Account', 'url' => '/user/view?id=' . Yii::$app->user->identity->id]),
                Yii::$app->user->isGuest ? '' : (['label' => 'My History', 'url' => '/user/myhistory']),
                Yii::$app->user->isGuest ? '' : (['label' => 'My Books',   'url' => '/user/mybooks']),
                Yii::$app->user->isGuest ? '' : $cancelCart,
                Yii::$app->user->isGuest ? '' : $clearCart,
                Yii::$app->user->isGuest ? '' : $modalButton,
                Yii::$app->user->isGuest ? (['label' => 'Signup', 'url' => ['/user/signup']]) : '',
            ],
        ]);

        echo Nav::widget([
            'options' => [
                'class' => 'navbar-nav ml-auto'
            ], 
            'items' => [
                Yii::$app->user->isGuest ? (['label' => 'Login', 'url' => ['/user/login']]) : ('<li>'
                    . Html::beginForm(['/user/logout'], 'post', ['class' => 'form-inline'])
                    . Html::submitButton(
                        'Logout (' . Yii::$app->user->identity->first_name . ')',
                        ['class' => 'btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'),
            ],
        ]);

        NavBar::end();

        if ($session->has('cart')) {
            echo $this->render('/book/_checkout');
        }
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