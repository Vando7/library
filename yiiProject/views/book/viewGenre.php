<?php



use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Book */
/* @var $form yii\widgets\ActiveForm */
/* @var $newGenre app\models\Genre */

?>

<div class="genre-modal" >
    <?php 
    Pjax::begin(['id' => 'genresList', 'enablePushState' => false]);

    echo '<div class="form-check">' . "\n";
    foreach($genreList as $genreObj){
        $deleteButton = Html::a("X",['deletegenre', 'id' => $genreObj->id,],[
            'class' => 'btn btn-danger btn-sm',
            'id' => 'deleteButton',
        ]);

        $genreLabel = Html::encode($genreObj->name);
        echo $deleteButton . $genreLabel . '<br>' . "\n";
    } 
    echo '</div>'. "\n";

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

    ?>

<?php Pjax::begin(['id' => 'new_genre', 'enablePushState' => true, 'timeout'=>false,], ) ?>    
    <div class="genre-form">

        
        <?php $form = ActiveForm::begin([
            'options' => ['data-pjax' => true ],
            'action' => 'creategenre'
        ]); ?>

<?= $form->field($newGenre, 'name')->textInput(['maxlength' => 200]) ?>

<div class="form-group">
            <?= Html::submitButton($newGenre->isNewRecord ? "Create" : "Haha Jonathan", ['class' => $newGenre->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ;?>
            
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        <?php ActiveForm::end(); ?>
        
        
    </div>
    <?php Pjax::end() ?>

</div>
