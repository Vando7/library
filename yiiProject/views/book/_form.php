<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap4\Modal;
use yii\widgets\Pjax;
use yii\jui\DatePicker;
use yii\helpers\VarDumper;

/* @var $this yii\web\View */
/* @var $model app\models\Book */
/* @var $form yii\widgets\ActiveForm */

$currentUser = Yii::$app->user->identity;

natcasesort($genreList);
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'published')->widget(DatePicker::classname(), [
        'dateFormat' => 'yyyy-MM-dd',
        'clientOptions' => [
            'changeYear' => 'true',
            'changeMonth' => 'true',
            'showButtonPanel' => true,
            'yearRange' => '1400:2040',
        ],
    ]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong">
        Select genres
    </button>

    <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Select book genres</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <?php
                    Pjax::begin(['id' => 'genresBookList',]);
                    echo $form->field($model, 'genreList')->checkboxList($genreList, ['separator' => '<br>']);
                    Pjax::end();
                    ?>
                </div>

                <div class="modal-footer">
                    <?php
                    $this->registerJs('
                         $("document").ready(function(){ 
                            $("#refresh").click(function() {
                                $.pjax.reload({container: \'#genresBookList\'});
                            });
                        });
                    ');
                    ?>

                    <span class="d-inline-block mr-auto" tabindex="0" data-toggle="tooltip" title="Open in new tab">
                        <a class="btn btn-primary mr-auto" href="/book/viewgenre?isModal=0" role="button" target="_blank">
                            Manage genres <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </span>

                    <span class="d-inline-block mr-auto" tabindex="0" data-toggle="tooltip" title="Warning: clears selection!">
                        <?= Html::Button('Refresh', ['class' => 'btn btn-primary', 'id' => 'refresh']) ?>
                    </span>

                    <button type="button" class="btn btn-success" data-dismiss="modal">Done</button>
                </div>
            </div>
        </div>
    </div>

    <?= $form->field($model, 'total_count')->textInput() ?>
    <!-- pepehmm -->
    <?= $form->field($model, 'bookCover')->fileInput(['accept' => 'image/*']) ?>

    <?= $form->field($model, 'bonusImages[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? "Save" : "Update", ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>