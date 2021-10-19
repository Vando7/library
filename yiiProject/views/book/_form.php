<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;
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

<div class="site-login">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off']]); ?>

    <?= $form->field($model, 'isbn',)->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>

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
    <hr>
    <?php
    if ($model->pictures != null) {
        
        $pictureJson = null;
        $pictureJson = json_decode($model->pictures, true);
        if ($pictureJson != null && array_key_exists('cover', $pictureJson)) {
            echo <<< HERE
            <div class="fluid-container">
                <div class="row no-gutters">
                    <div class="col-md">
                        <div class="menu-image h-100 d-flex justify-content-center">
                            <img class="img-fluid" src="/{$pictureJson["cover"]}" alt="Card image cap" style="max-height:300px;">
                        </div>
                    </div>

                    <div class="col-md ">
                        <h2 class="card-title">Book cover</h2>
                        {$form->field($model, 'bookCover')->fileInput(['accept' => 'image/*'])->label('Choose new cover picture:')}
                    </div>
                </div>
            </div>
            HERE;
        } else {
            echo $form->field($model, 'bookCover')->fileInput(['accept' => 'image/*']);
        }
    } else {
        echo $form->field($model, 'bookCover')->fileInput(['accept' => 'image/*']);
    }
    ?>
    <hr>
    <?php
    if ($model->pictures != null) {
        echo '<h3>Add, rearrange or remove bonus pictures:</h3>';
        
        $hasExtra = false;
        Pjax::begin(
            ['enableReplaceState'=>false, 'enablePushState'=>false, 'timeout' => 5000 ]
        );
        //echo Html::encode(VarDumper::dumpAsString(json_decode($model->pictures,true)));
        
        $pictureJson = null;
        $pictureJson = json_decode($model->pictures, true);
        $counter = 1;

        echo '<div class="row">';
        while (array_key_exists('extra' . $counter, $pictureJson)) {
            $hasExtra = true;
            $picPath = $pictureJson['extra' . $counter];

            if(file_exists('upload/'.$model->isbn.'_extra'.$counter.'-thumb.jpeg')){
                $picPath = 'upload/'.$model->isbn.'_extra'.$counter.'-thumb.jpeg';
            }

            echo '<div class="col-auto mb-4">';
            echo
            '<div class="card w-200">
                    <img class="rounded mx-auto d-block " src="/' . $picPath . '" alt="Card image cap" style="height:150px; width:150px; object-fit: contain;">
                    <div class="card-body"></div>
                    <div class="btn-group card-footer" role="group" aria-label="Basic example">';

            if ($counter > 1) {
                echo Html::a('<i class="bi bi-arrow-left"></i>', [
                    'movepic', 
                    'isbn' => $model->isbn,
                    'picIndex'=> $counter,
                    'direction' => 'left',
                ], 
                ['class' => 'btn btn-primary btn-sm']);
                //echo '<button type="button" class="btn btn-primary"><i class="bi bi-arrow-left"></i></button>';
            }

            echo Html::a('<i class="bi bi-trash"></i>', [
                'deletepic', 
                'isbn' => $model->isbn,
                'picIndex' => $counter,
            ], 
            [
                'class' => 'btn btn-danger btn-sm',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]);

            if (array_key_exists('extra' . ($counter+1), $pictureJson)) {
                echo Html::a('<i class="bi bi-arrow-right"></i>', [
                    'movepic', 
                    'isbn' => $model->isbn,
                    'picIndex'=> $counter,
                    'direction' => 'right',
                ], 
                ['class' => 'btn btn-primary btn-sm']);
            }

            echo '</div></div></div>';

            $counter++;
        }
        echo '</div>';

        echo $hasExtra == true ? Html::a('<i class="bi bi-trash"></i> Clear All', [
            'deleteallpics', 
            'isbn' => $model->isbn,
        ], 
        [
            'class' => 'btn btn-danger mb-10',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) : '';
        
        Pjax::end();
    }
    ?>

    <?= $form->field($model, 'bonusImages[]')->fileInput(['multiple' => true, 'accept' => 'image/*'])->label("Add images") ?>
    
    <hr>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? "Save" : "Update", ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    
    </div>

</div>