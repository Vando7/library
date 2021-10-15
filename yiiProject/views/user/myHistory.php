<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\LentToSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Books I have taken'
?>
<div class="lentTo-index" style="margin:auto;max-width:850px;">

    <h1>Full history of the books I have taken</h1>

    <?= $this->render('_searchMyHistory', ['model' => $searchModel]) ?>

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
                'label'  => 'Relevant dates',
                'value'  => function ($model) {
                    $element = '';

                    if($model->status == 'reserved'){
                        $element .= "<b>Take book:</b> Today<br>";

                        return $element;
                    }

                    // Date lent
                    $element .= '<b>Given</b> ' . Html::encode(date("F jS, Y", strtotime($model->date_lent))) . "<br>";

                    // Date returned 
                    if ($model->status == 'returned') {
                        $element .= "<b>Returned</b> " . ($model->date_returned ? date("F jS, Y", strtotime($model->date_returned)) : "No") . '<br>';
                    }

                    // Deadline 
                    $element .= "<b>Deadline</b> " . (date("F jS, Y", strtotime($model->deadline))) . "<br>";

                    return $element;
                }
            ],
        ],
    ]);
    ?>
</div>