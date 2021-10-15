<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lentTo-search text-right">

    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'options' => [
            'data-pjax' => 1,
        ]
    ]); ?>

    <div class="btn-group" role="group" aria-label="Basic example">
        <?= $form->field($model, 'statusQuery')->dropDownList(
            [
                'taken'    => 'Not Returned',
                'late'     => 'Past Deadline',
            ],
            ['onchange'=>'this.form.submit()'],
            ['prompt'   => 'Filter by']
        )->label(false); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>