<?php

use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BookSearch */
/* @var $form yii\widgets\ActiveForm */
error_log(VarDumper::dumpAsString($model),3,'ivan_log.txt');
?>

<div class="book-search" style="margin:auto;max-width:500px;">

    <?php $form = ActiveForm::begin([
        'action' => ['return', 'id'=>$user_id],
        'method' => 'get',
    ]); ?>


    <?= $form->field($model, 'titleQuery')
        ->textInput(['placeholder' => 'Title'])
        ->label('Book search', ['class' => 'label-class']) ?>

        <div class="form-group">
            <?= Html::submitButton('<i class="bi bi-search"></i> Search', ['class' => 'btn btn-primary ml-2']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>