<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model app\models\SignupForm */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\widgets\Pjax;

$this->title = 'Signup';
$redStar = '<b class="text-danger"> *</b>';
?>

<div class="site-login" style="margin:auto;max-width:500px;">
    <h1><?= HTML::encode($this->title) ?></h1>

    <?php $form=ActiveForm::begin([
        'id'     => 'signup-form',
        'enableClientValidation' => 1,
    ])?>

    <?= $form->field($model, 'first_name')
            ->textInput(['autofocus' => true])
            ->label($model->getAttributeLabel('first_name').$redStar) ?>

    <?= $form->field($model, 'last_name')
            ->textInput()
            ->label($model->getAttributeLabel('last_name').$redStar) ?>

    <?php //Pjax::begin(); ?>
    <?= $form->field($model, 'email', ['enableAjaxValidation' => 1])
            ->textInput(['class'=>'form-control'])
            ->label($model->getAttributeLabel('email').$redStar) ?>
    <?php //Pjax::end(); ?>

    <?= $form->field($model, 'phone',['enableAjaxValidation' => 1])
            ->textInput(['class'=>'form-control'])
            ->label($model->getAttributeLabel('phone').$redStar) ?>

    <?= $form->field($model, 'country')
            ->textInput()
            ->label($model->getAttributeLabel('country').$redStar) ?>

    <?= $form->field($model, 'city')
            ->textInput()
            ->label($model->getAttributeLabel('city').$redStar) ?>

    <?= $form->field($model, 'street')
            ->textInput()
            ->label($model->getAttributeLabel('street').$redStar) ?>

    <p class='text-secondary'>
        <small> 
            <i class="bi bi-info-circle"></i> Password must contain at least one letter and one number. Minimum length is 6.
        </small>
    </p>
    
    <?= $form->field($model, 'password')
            ->passwordInput() 
            ->label($model->getAttributeLabel('password').$redStar) ?>

    <?= $form->field($model, 'password_repeat')->passwordInput()
            ->passwordInput() 
            ->label($model->getAttributeLabel('repeat_password').$redStar) ?>
    
    

    <div class="form-group text-center">
        <div class="offset-lg-1 col-lg-11">
            <?= Html::submitButton('Sign up', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>
    </div>
    
    <p class='text-secondary text-center'>
            <?= "Fields marked with ".$redStar." are required."?>
    </p>
    <p class="text-center text-secondary">Already have an account? <a href="login">Sign in.</a></p>

    <?php ActiveForm::end(); ?>
</div>