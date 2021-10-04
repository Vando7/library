<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Update User: ' . $model->first_name . " " . $model->last_name;

// Define current user. 
$currentUser = Yii::$app->user->identity;
?>
<div class="user-update container" style="margin:auto;max-width:500px;">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php 
    if($currentUser->role == 'admin' ){
        echo $this->render('_formAdmin', ['model' => $model]);
    } 
    elseif ($currentUser->role == 'librarian'){
        if($currentUser->id == $model->id){
            echo $this->render('_form', ['model' => $currentUser]);
        }
        else {
            echo $this->render('_formLibrarian', ['model' => $model]);
        }
    }else{
        echo $this->render('_form', ['model' => $model]);
    }
    ?>

</div>
