<?php

use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\LentToSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<h4>List of users and the amount of books they have not returned.</h4>
<?php Pjax::begin();?>
<?= GridView::widget([
    'dataProvider' => $dataProviderDays,
    'tableOptions' => [
        'class' => 'table table-striped',
    ],
    'columns' => [
        [
            'format' => 'raw',
            'label'  => 'Reader',
            'value'  => function ($model) {
                $element = '';
                // User names
                $user = $model->user;
                $element .=  '<i class="bi bi-person"></i> ' . Html::a(Html::encode($user->first_name . " " . $user->last_name), "/user/view?id=" . $user->id) . "<br>";

                // User Phone
                $element .= '<i class="bi bi-telephone"></i> ' . Html::encode($user->phone) . "<br>";

                // User Email
                $element .= '<i class="bi bi-envelope"></i> ' . Html::encode($user->email) . "<br>";

                return $element;
            }
        ],
        [
            'format' => 'raw',
            'label'  => 'Books taken',
            'value'  => function ($model) {
                $element = '';
                $element .= '<h3>';
                $element .= Html::encode($model->amount);
                $element .= '</h3>';
                return $element;
            }
        ],
    ],
]);
?>
<?php 
    $fixPagination = <<< JS
    $(function(){
            $("ul.pagination > li > a").addClass("page-link");
            $("ul.pagination").addClass('justify-content-center');
        });
    JS;
    $this->registerJs($fixPagination);
    Pjax::end();
?>