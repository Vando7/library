<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BookSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="book-search" style="margin:auto;max-width:500px;">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>


    <?= $form->field($model, 'globalSearch')
                ->textInput(['placeholder' => 'Title, Author, ISBN etc.'])
                ->label('Book search',['class'=>'label-class'])?>

    <div class="btn-group" role="toolbar" aria-label="Toolbar with button groups">

        <?= $form->field($model, 'genreSearch', ['template' => "
            <div class='dropdown'>

                <button
                class='btn btn-info dropdown-toggle'
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

        <div class="form-group">
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary ml-2']) ?>
            <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
