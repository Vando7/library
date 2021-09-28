<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Book', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'isbn',
            'pictures:ntext',
            'title',
            'author',
            'published',
            //'description:ntext',
            //'total_count',
            //'available_count',

            ['class' => 'yii\grid\ActionColumn',
             'urlCreator' => function($action,$model,$key,$index){
                 if($action == 'view'){
                     return 'view?isbn='.$model->isbn;
                 }

                 if($action == 'update'){
                     return 'update?isbn='.$model->isbn;
                 }

                 if($action == 'delete'){
                     return 'delete?isbn='.$model->isbn;
                 }
             }],
        ],
    ]); ?>


</div>
