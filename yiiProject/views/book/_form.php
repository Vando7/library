<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Book */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'published')->widget(\yii\jui\DatePicker::classname(), [
    //'language' => 'ru',
    'dateFormat' => 'yyyy-MM-dd',
    'clientOptions' =>[
        'changeYear' => 'true',
        'changeMonth' => 'true',
        'showButtonPanel' => true,
        'yearRange' => '1400:2040',
    ],
    ]) ?>
        
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    
    <?= $form->field($model, 'total_count')->textInput() ?>
    <!-- pepehmm -->
    <?= $form->field($model, 'bookCover')->fileInput(['accept' => 'image/*']) ?>

    <?= $form->field($model, 'bonusImages[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

    <div class="form-group">
        <?= Html::submitButton('Add', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
