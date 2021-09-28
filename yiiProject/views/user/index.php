<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Yii::$app->user->isGuest ? '' : ( Yii::$app->user->identity->role == 'admin' ? (Html::a('Create User', ['create'], ['class' => 'btn btn-success'])):'') ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'first_name',
            'last_name',
            'country',
            //'city',
            //'street',
            //'phone',
            'email',
            //'password',
            //'role',
            //'note:ntext',
            //'register_date',
            //'suspended_status',
            //'suspended_date',
            //'suspended_reason:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
