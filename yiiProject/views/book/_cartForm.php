<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
?>

<div class="input-form">
    <?php
    Pjax::begin();
    $form = ActiveForm::begin([
        'action' => [
            'addtocart',
            'isbn' => $model->isbn,
            'availableBooks' => $model->available_count,
        ],
        'options' => [
            'data-pjax' => 1,
        ],
    ]); ?>

    <div class="btn-group" role="group" aria-label="Basic example">

        <?= $form->field($cart, 'amount')
            ->textInput([
                'type' => 'number',
                'placeholder' => 'Select book amount',
                'max' => $model->available_count,
                'value' => 1,
            ])
            ->label(false); ?>

        <div class="form-group ml-3">
            <?= Html::submitButton('Add', ['class' => 'btn btn-primary']) ?>
        </div>

    </div>
    <?php
    ActiveForm::end();
    Pjax::end();
    ?>
</div>