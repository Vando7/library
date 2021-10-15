<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search text-right">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1,
        ]
    ]); ?>


    <div class="btn-group" role="group" aria-label="Basic example">
        <?= $form->field($model, 'globalSearch')
            ->textInput(['placeholder' => 'Names, e-mail, phone etc.'])
            ->label(false); ?>

        <div class="form-group ml-3">
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>