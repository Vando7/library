<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lentTo-search text-right">

    <?php $form = ActiveForm::begin([
        'action' => ['mybooks'],
        'method' => 'get',
    ]); ?>

    <div class="btn-group" role="group" aria-label="Basic example">
        <?= $form->field($model, 'statusQuery')->dropDownList([
            'taken'    => 'Not Returned',
            'late'     => 'Past Deadline',
        ], ['prompt'   =>'Filter by']
        )->label(false);?>

        <div class="form-group ml-3">
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
