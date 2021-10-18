<?php

use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\LentToSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Problematic Users';
?>

<div class="lentTo-index" style="margin:auto;max-width:850px;">

    <h1><?= Html::encode($this->title) ?></h1>

    <ul class="nav nav-tabs mb-3 nav-fill" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Not Returned</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Past Deadline</a>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            <?= $this->render('_problemReaderDays', [
                'dataProviderDays' => $dataProviderDays
            ]) ?>
        </div>

        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <?= $this->render('_problemReaderLate', [
                'dataProviderLate' => $dataProviderLate
            ]) ?>
        </div>
    </div>
    
</div>
