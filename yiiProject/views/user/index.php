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
        <?= Yii::$app->user->isGuest ? '' : 
            ( Yii::$app->user->identity->role == 'admin' ?
                (Html::a('Create User', ['create'], ['class' => 'btn btn-success']))
                :
                '') ?>
    </p>

    <?= $this->render('_search', ['model' => $searchModel]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'format' => 'raw',
                'label'  => 'User info',
                'value'  => function($model){
                    // First and last names - clickable
                    $element = '';
                    $element .= '<b><i class="bi bi-person"></i> ';
                    $element .= Html::a( Html::encode($model->first_name. " " .$model->last_name),"view?id=".$model->id );
                    $element .= '</b><br>';

                    // Phone number
                    $element .= '<i class="bi bi-telephone"></i> ';
                    $element .= Html::encode($model->phone) . '<br>';

                    // E-mail
                    $element .= '<i class="bi bi-envelope"></i> ';
                    $element .= Html::encode($model->email) . '<br>';

                    return $element;
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
