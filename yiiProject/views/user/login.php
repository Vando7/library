<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form ActiveForm */

$this->title = 'Login';
?>
<div class="site-login" style="margin:auto;max-width:500px;">

    <h1><?= HTML::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'email')->textInput() ?>
    <?= $form->field($model, 'password')->passwordInput() ?>

    <div class="form-group text-center">
        <?= Html::submitButton('Sign in', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    <p class="text-center text-secondary">Don't have an account? <a href="signup">Sign up.</a></p>

</div>