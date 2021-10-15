<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->first_name . " " . $model->last_name;

// why am i calling this
\yii\web\YiiAsset::register($this);

$currentUser = Yii::$app->user->identity;
?>


<div class="user-view" style="margin:auto;max-width:700px;">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<i class="bi bi-pencil-square"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('☠️ Suspend', ['suspend', 'id' => $model->id], ['class' => 'btn btn-danger']) ?>
        <?= $currentUser->role == 'admin' ? ( // Render delete button only for admin.
            Html::a(
                'Delete',
                ['delete', 'id' => $model->id],
                [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]
            )) : ''; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        
        'formatter' => [
            'class' => '\yii\i18n\Formatter',
            'dateFormat' => 'medium',
            'datetimeFormat' => 'medium',
        ],
        'attributes' => [
            'first_name',
            'last_name',
            'country',
            'city',
            'street',
            'phone',
            'email:email',
            'role',
            'note:ntext',
            'register_date:date',
            'suspended_status',
            'suspended_date:date',
            'suspended_reason:ntext',
        ],
    ]) ?>

    <ul class="nav nav-tabs mb-3 nav-fill" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Not Returned</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Whole history</a>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            <?php
            Pjax::begin();
            echo $this->render('myBooks', [
                'searchModel' => $searchModel,
                'dataProvider' => $myBooksDataProvider,
            ]);
            Pjax::end();
            ?>
        </div>

        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <?php
            Pjax::begin();
            echo $this->render('myHistory', [
                'searchModel' => $searchModel,
                'dataProvider' => $historyDataProvider,
            ]);
            Pjax::end();
            ?>
        </div>
    </div>

</div>