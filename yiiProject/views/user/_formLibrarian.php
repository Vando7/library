<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'first_name',['readonly'=>true])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name',['readonly'=>true])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'country',['readonly'=>true])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city',['readonly'=>true])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'street', ['readonly'=>true])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone', ['readonly'=>true])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email',['readonly'=>true])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'role', ['readonly'=>true])->dropDownList([ 'reader' => 'Reader', 'librarian' => 'Librarian', 'admin' => 'Admin', ], ['prompt' => 'Set user role']) ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'register_date',['readonly'=>true])->textInput() ?>

    <?= $form->field($model, 'suspended_status',['readonly'=>true])->dropDownList([ 'no' => 'No', 'yes' => 'Yes', ], ['prompt' => 'Set suspended status']) ?>

    <?= $form->field($model, 'suspended_date')->textInput() ?>

    <?= $form->field($model, 'suspended_reason')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
