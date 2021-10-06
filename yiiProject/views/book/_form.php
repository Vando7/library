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
?>

<div class="book-form">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal'],
    ],
    ); ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'published')->widget(DatePicker::classname(), [
    //'language' => 'ru',
    'dateFormat' => 'yyyy-MM-dd',
    'clientOptions' =>[
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
                        echo $form->field($model, 'genreList')->checkboxList($genreList,['separator' => '<br>']);
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
                    <?= Html::Button('Refresh', ['class' => 'btn btn-primary', 'id'=>'refresh']) ?>  
                    <?= $currentUser->role == 'reader' ? '' : Html::Button('Manage Genres', [
                        'value' => Url::to('/book/viewgenre'), 
                        'class' => 'btn btn-primary', 
                        'id'    => 'genreModalButton',
                        ])?>

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

    <?php $modal = Modal::begin([
            'title' => 'Manage genres', 
            'id'    => 'genreModal',
        ]); 
        
        echo '<div id="genreModalContent"></div>';
        Modal::end();
    ?>
    

</div>
