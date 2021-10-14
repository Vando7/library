<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\VarDumper;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LentToSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Return books";
?>

<div class="container" style="margin:auto;max-width:850;">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= $this->render('_searchReturn', ['model' => $searchModel, 'user_id'=>$user_id]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'format' => 'raw',
                'label'  => 'Book info',
                'value'  => function ($model) {
                    // Book name
                    $element  = '';
                    $element .= '<b><i class="bi bi-book"></i> ';
                    $element .= Html::a(Html::encode($model->bookIsbn->title), "/book/view?isbn=" . $model->book_isbn);
                    $element .= '</b><br>';

                    // Amount
                    $element .= '<i>';
                    $element .= Html::encode($model->amount) . " " . ($model->amount > 1 ? " Copies" : " Copy") . " ";
                    $element .= '</i>';

                    // Status
                    $badgeType = '';
                    $status    = $model->status;
                    if ($model->status == 'returned') $badgeType = 'success';
                    if ($model->status == 'reserved') $badgeType = 'warning';
                    if ($model->status == 'taken') {
                        $deadline = strtotime($model->deadline);
                        $today    = strtotime('now');

                        if ($deadline < $today) {
                            $badgeType = 'danger';
                            $status = 'Past Deadline';
                        } else {
                            $badgeType = 'primary';
                            $status = 'Not Returned';
                        }
                    }
                    $element .= '<span class="badge badge-' . $badgeType . '">' . $status . '</span><br>';
                    // ISBN
                    $element .= "ISBN " . Html::encode($model->book_isbn) . '<br>';

                    return $element;
                }
            ],
            [
                'format' => 'raw',
                'label'  => 'Info',
                'value'  => function ($model) {
                    $element = '';

                    // Date lent
                    $element .= '<b>Given</b> ' . Html::encode(date('Y-m-d', strtotime($model->date_lent))) . "<br>";

                    // Deadline 
                    $element .= "<b>Deadline</b> " . (date('Y-m-d', strtotime($model->deadline))) . "<br>";

                    // Return button
                    $element .= Html::a(
                        'Return <i class="bi bi-box-arrow-in-down-left"></i>',
                        "/book/returnbook?id={$model->user_id}&isbn={$model->book_isbn}&dateLent={$model->date_lent}",
                        ['class' => 'btn btn-warning'],
                    );

                    $element .= '</b><br>';

                    return $element;
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>