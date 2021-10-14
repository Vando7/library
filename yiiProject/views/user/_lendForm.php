<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LentTo */
/* @var $form ActiveForm */
?>
<div class="lendHistory">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'book_isbn') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'employee_id') ?>

    <?= $form->field($model, 'amount') ?>

    <?= $form->field($model, 'deadline') ?>
    
    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'date_lent') ?>

    <?= $form->field($model, 'date_returned') ?>


    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- lendHistory -->