<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Books';
$currentUser = Yii::$app->user->identity;

?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= $currentUser->role == 'reader' ? '' : Html::a('Create Book', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => 'ISBN',
                'format' => 'raw',
                'value' => function ($model){ 
                    return Html::a(Html::encode($model->isbn),'view?isbn='.$model->isbn);
                },
            ],
            'pictures:ntext',
            'title',
            'author',
            'published',
            //'description:ntext',
            //'total_count',
            //'available_count',
            $currentUser->role == 'reader' ? 'Hi' :(
            ['class' => 'yii\grid\ActionColumn',
             'urlCreator' => function($action,$model){
                 if($action == 'view'){
                     return 'view?isbn='.$model->isbn;
                 }

                 if($action == 'update'){
                     return 'update?isbn='.$model->isbn;
                 }

                 if($action == 'delete'){
                     return 'delete?isbn='.$model->isbn;
                 }
             }
            ]
            )
        ],
    ]); ?>


</div>
