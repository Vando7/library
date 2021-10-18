<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\LentToSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reserved books';
?>
<div class="lentTo-index" style="margin:auto;max-width:850px;">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::a(
        "<i class='bi bi-x-lg'></i> Cancel All",
        "/user/cancelreservedall",
        [
            'class' => 'btn btn-sm btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to cancel all reservations?',
                'method' => 'post',
            ],
        ]
    ); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'format' => 'raw',
                'label'  => 'User',
                'value'  => function ($model) {
                    $element = '';

                    // User names
                    $user = $model->user;
                    $element .=  '<i class="bi bi-person"></i><b>' . Html::a(Html::encode($user->first_name . " " . $user->last_name), "/user/view?id=" . $user->id) . "</b><br>";

                    // User Phone
                    $element .= '<i class="bi bi-telephone"></i> ' . Html::encode($user->phone) . "<br>";

                    return $element;
                }
            ],
            [
                'format' => 'raw',
                'label'  => 'Book reserved',
                'value'  => function ($model) {
                    // Book name
                    $element  = '';
                    $element .= '<b><i class="bi bi-book"></i> ';
                    $element .= Html::a(Html::encode($model->bookIsbn->title), "/book/view?isbn=" . $model->book_isbn);
                    $element .= '</b><br>';

                    // ISBN
                    $element .= "<b>ISBN</b> " . Html::encode($model->book_isbn) . " ";

                    // Cancel button
                    $element .= Html::a("<i class='bi bi-x-lg'></i> Cancel", "/user/cancelreserved?isbn={$model->book_isbn}&userID={$model->user_id}", ['class' => 'btn btn-sm btn-danger']);

                    return $element;
                }
            ],
        ],
    ]);
    ?>
    <?php 
    $script = <<< JS
        $(function(){
                $("ul.pagination > li > a").addClass("page-link");
            });
    JS;
    $this->registerJs($script);
    ?>
</div>