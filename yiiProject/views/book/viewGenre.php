<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Book */
/* @var $form yii\widgets\ActiveForm */
/* @var $newGenre app\models\Genre */

?>

<div class="genre-modal" style="margin:auto;max-width:500px;">

    <?php
    Pjax::begin(['id' => 'genresList', 'enablePushState' => false]);

    echo '<div class="form-check">' . "\n";

    foreach ($genreList as $genreObj) {
        if ($genreObj) {
            $deleteIcon = '<i class="bi bi-x-lg"></i>';

            $deleteButton = Html::a($deleteIcon, ['deletegenre', 'id' => $genreObj->id,], [
                'class' => 'badge badge-danger',
                'id' => 'deleteButton',
                "onclick"=>"if (!confirm('Are you sure?\\r\\nGenre deletions are not recoverable.')){return}",
            ]);

            $genreLabel = Html::encode($genreObj->name);
            echo $deleteButton . " " . $genreLabel . '<br>' . "\n";
        }
    }

    echo '</div>' . "\n";

    Pjax::end();

    $this->registerJs(
        '$("document").ready(function(){ 
            $("#new_genre").on("pjax:end", function() {
                $.pjax.reload({container:"#genresList"});  //Reload list
            });
        });
        $("document").ready(function(){ 
            $("#deleteButton").on("pjax:end", function() {
                $.pjax.reload({container:"#genresList"});  //Reload list
            });
        });'
    );

    Pjax::begin(['id' => 'new_genre', 'enablePushState' => true, 'timeout' => false,],)
    ?>

    <div class="genre-form">
        <?php $form = ActiveForm::begin([
            'options' => ['autocomplete' => 'off', 'data-pjax' => true],
            'action' => ['creategenre', 'goBack'],
        ]); ?>

        <?= $form->field($newGenre, 'name', )->textInput(['maxlength' => 40, 'autocomplete'=>'off']) ?>

        <div class="form-group">
            <?= Html::submitButton($newGenre->isNewRecord ? "Create" : "Haha Jonathan", ['class' => $newGenre->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <?php Pjax::end() ?>
</div>