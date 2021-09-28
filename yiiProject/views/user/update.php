<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Update User: ' . $model->first_name . " " . $model->last_name;

// Define current user. 
$currentUser = Yii::$app->user->identity;
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    
    <?= $currentUser->role == 'reader' ? 
    $this->render('_form', ['model' => $model])
    :(
        $currentUser->role == 'librarian' && $currentUser->id != $model->id?
            $this->render('_formLibrarian', ['model' => $model])
            :
            $this->render('_form', ['model' => $model]))
     ?>

</div>
