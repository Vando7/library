<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Book */

$this->title = 'Create Book';
?>
<div class="book-create" style="margin:auto;max-width:500px;">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
            'model' => $model,
            'genreList' => $genreList,
    ]) ?>

</div>
