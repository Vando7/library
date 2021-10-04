<?php


use yii\bootstrap4\Modal;
use yii\helpers\Html;
use app\models\Genre;

/* @var $this yii\web\View */
/* @var $model app\models\Book */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="genre-modal" >
    <?php $modal = Modal::begin(['toggleButton' => ['label' => 'Manage Genre'], 'title' => 'Manage genres']);
    $modal-> title  = "genre";
     $genreDB = New Genre;
     $genreList = $genreDB->find()->all();
    //  foreach($genreList as $genreObj){
    //      echo Html::encode($genreObj->name) . "<br>";
    // } 

    echo '<div class="form-check">';
    foreach($genreList as $genreObj){
        echo '<input class="form-check-input" type="checkbox" value="" id="'. Html::encode($genreObj->id) . '"checked>';
        echo '<label class="form-check-label" for="'. Html::encode($genreObj->id) .'">'. Html::encode($genreObj->name) .'</label><br>';
    } 
    echo '</div>';
   
    ?>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon3">New genre</span>
        </div>

        <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3">

        <div class="input-group-append">
            <button class="btn btn-primary" type="button" id="button-addon2">Add</button>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" data-dismiss="modal">Save</button>
    </div>

    
    <?php Modal::end(); ?>
</div>
