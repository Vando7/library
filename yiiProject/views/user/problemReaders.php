<?php

use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\LentToSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<div class="lentTo-index" style="margin:auto;max-width:850px;">

    <h1><?= Html::encode($this->title) ?></h1>

    <ul class="nav nav-tabs mb-3 nav-fill" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Not Returned</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Past Deadline</a>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            <?= GridView::widget([
                'dataProvider' => $dataProviderDays,
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
        </div>

        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <?= GridView::widget([
                'dataProvider' => $dataProviderLate,
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
                        'label'  => 'Days late',
                        'value'  => function ($model) {
                            $element = '';
                            $element .= '<h3>';
                            $deadline = new DateTime($model->deadline);
                            $today = new DateTime();
                            $days = $deadline->diff($today)->days;
                            $element .= $days;
                            $element .= '</h3>';
                            return $element;
                        }
                    ],
                ],
            ]);
            ?>
        </div>

    </div>

</div>
</div>