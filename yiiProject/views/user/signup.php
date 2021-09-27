<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model app\models\SignupForm */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login">
    <h1><?= HTML::encode($this->title) ?></h1>
    
    <p> Please fill out the following fields to register. </p>

    <?php $form=ActiveForm::begin([
            'id' => 'signup-form',
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-1 col-form-label'],
            ],
        ])?>

        <?= $form->field($model, 'first_name')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'last_name')->textInput() ?>
        <?= $form->field($model, 'email')->textInput() ?>
        <?= $form->field($model, 'phone')->textInput() ?>
        <?= $form->field($model, 'country')->textInput() ?>
        <?= $form->field($model, 'city')->textInput() ?>
        <?= $form->field($model, 'street')->textInput() ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'password_repeat')->passwordInput() ?>
        <div class="form-group">
            <div class="offset-lg-1 col-lg-11">
                <?= Html::submitButton('Sign up', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>