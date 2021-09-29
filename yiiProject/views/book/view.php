<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Book */

$this->title = $model->title;
$currentUser = Yii::$app->user;

\yii\web\YiiAsset::register($this);
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= $currentUser->can('manageBook') ? Html::a('Update', ['update', 'isbn' => $model->isbn], ['class' => 'btn btn-primary']) : '' ?>
        <?= $currentUser->can('manageBook') ? Html::a('Delete', ['delete', 'isbn' => $model->isbn], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) 
        : '';?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'isbn',
            'pictures:ntext',
            'title',
            'author',
            'published',
            'description:ntext',
            'total_count',
            'available_count',
        ],
    ]) ?>

</div>
