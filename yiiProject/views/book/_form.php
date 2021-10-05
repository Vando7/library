<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap4\Modal;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Book */
/* @var $form yii\widgets\ActiveForm */

$currentUser = Yii::$app->user->identity;
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'published')->widget(\yii\jui\DatePicker::classname(), [
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

    <?= $form->field($model, 'total_count')->textInput() ?>
    <!-- pepehmm -->
    <?= $form->field($model, 'bookCover')->fileInput(['accept' => 'image/*']) ?>

    <?= $form->field($model, 'bonusImages[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>
    <p>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? "Save" : "Update", ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

   
    <?= $currentUser->role == 'reader' ? '' : Html::Button('Manage Genres', [
            'value' => Url::to('/book/viewgenre'), 
            'class' => 'btn btn-primary', 
            'id'    => 'genreModalButton',
    ])?>
    </p>

    <?php $modal = Modal::begin([
            'title' => 'Manage genres', 
            'id' => 'genreModal',
        ]); 
        
        echo '<div id="genreModalContent"></div>';
        Modal::end();
    ?>
    

</div>
