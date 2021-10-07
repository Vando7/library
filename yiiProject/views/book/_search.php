<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BookSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="book-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'globalSearch')->textInput(['placeholder' => 'Search']); ?>

    <?= $form->field($model, 'genreSearch', ['template' => "
        <div class='dropdown'>

            <button
            class='btn btn-default dropdown-toggle'
            data-toggle='dropdown'
            type='button'>
                <span>Select genres</span>
                <span class='caret'></span>
            </button>

            <div style=\"overflow-y:scroll;\">
            {input}
            </div>

        </div>"])->checkboxList($genreList,
        [
            'tag' => 'ul',
            'class' => 'dropdown-menu',
            'style' => 'height:300px; overflow-y:auto;',
            'item' => function ($index, $label, $name, $checked, $value) {
                return '<a class="dropdown-item" href="#">' . Html::checkbox($name, $checked, [
                    'value' => $value,
                    'label' => Html::encode($label),
                ]) . '</a>';
            }
    ]); ?>

    <?php // $form->field($model, 'pictures') ?>

    <?php // $form->field($model, 'title') ?>

    <?php // $form->field($model, 'author') ?>

    <?php // $form->field($model, 'published') ?>

    <?php // $form->field($model, 'description') ?>

    <?php // $form->field($model, 'total_count') ?>

    <?php // $form->field($model, 'available_count') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
