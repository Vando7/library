<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\LentToSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="lentTo-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin();?>
    <?= $this->render('_searchLentTo', ['model' => $searchModel]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            /*
            [
                'format' => 'raw',
                'label'  => 'User info',
                'value'  => function($model){
                    // First and last names - clickable
                    $element = '';
                    $element .= '<b>';
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
            */
            'book_isbn',
            'user_id', 
            'employee_id',
            'amount',
            'date_lent',
            'date_returned',
            
        ],
    ]); ?>
    <?php Pjax::end();?>

</div>
