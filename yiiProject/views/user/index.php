<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap4\Modal;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
?>
<div class="user-index" style="margin:auto;max-width:850px;">

    <h1><?= Html::encode($this->title) ?></h1>


    <p>
        <?= Yii::$app->user->isGuest ? '' : (Yii::$app->user->identity->role == 'admin' ?
                (Html::a('Create User', ['create'], ['class' => 'btn btn-success']))
                :
                '') ?>
    </p>

    <?= $this->render('_search', ['model' => $searchModel]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => [
            'class' => 'table table-striped',
        ],
        'columns' => [
            [
                'format' => 'raw',
                'label'  => 'User info',
                'value'  => function ($model) {
                    // First and last names - clickable
                    $element = '';
                    $element .= '<b><i class="bi bi-person"></i> ';
                    $element .= Html::a(Html::encode($model->first_name . " " . $model->last_name), "view?id=" . $model->id);
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
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{give} {return}',
                'buttons' => [
                    'give' => function ($url, $model) {
                        if($model->suspended_status == 'yes'){
                            return html::a('Suspended ☠', '#', ['give', 'class' => "btn btn-danger disabled"]);
                        }
                        return html::a('Give <i class="bi bi-book-half"></i>', $url, ['give', 'class' => "btn btn-success"]);
                    },
                    'return' => function ($url, $model) {
                        if (yii::$app->session->has('cart')) return '';
                        $element  = '';
                        $element .= Html::a('Return <i class="bi bi-box-arrow-in-down-left"></i>', "/book/return?id=" . $model->id, [
                            'class' => 'btn btn-warning'
                        ]);
                        $element .= '</b><br>';
                        return $element;
                    }
                ]
            ],
        ],
    ]); ?>
    <?php 
    $script = <<< JS
        $(function(){
                $("ul.pagination > li > a").addClass("page-link");
                $("ul.pagination").addClass('justify-content-center');
            });
    JS;
    $this->registerJs($script);
    ?>
</div>