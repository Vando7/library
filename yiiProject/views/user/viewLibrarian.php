<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->first_name." ".$model->last_name;
\yii\web\YiiAsset::register($this);
$currentUser = Yii::$app->user->identity;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= $currentUser->role == 'admin' ? ( // Render delete button only for admin.
                Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]
                )
            ):''; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'first_name',
            'last_name',
            'country',
            'city',
            'street',
            'phone',
            'email:email',
            'role',
            'note:ntext',
            'register_date',
            'suspended_status',
            'suspended_date',
            'suspended_reason:ntext',
        ],
    ]) ?>

</div>
