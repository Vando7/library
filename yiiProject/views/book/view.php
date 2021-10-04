<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model app\models\Book */

$this->title = $model->title;
$this->registerCssFile("/css/bookView.css");
$currentUser = Yii::$app->user;
$pictureJson = json_decode($model->pictures, true);

\yii\web\YiiAsset::register($this);
?>

<div class="book-view" style="margin-top: 50px;">
    <!-- scuff fix -->


    <div class="container">
        <div class="row">
            <!-- ||||||| -->
            <div class = "col-sm-4 col-md-6 col-lg-5" id="carouselColumn">
                <div id="pictureCarousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner" >
                        <div class="carousel-item active">
                            <?= $model->pictures ? 
                                '<img class="w-100 d-block" src="/' . Html::encode($pictureJson['cover']) . '"alt="First slide">' 
                                : ''?>
                        </div>
                        
                        <?php 
                            if($model->pictures){
                                for($i = 1; $i < count($pictureJson); $i++){
                                    echo '<div class="carousel-item">';
                                    echo '<img class="w-100 d-block" src="/' . Html::encode($pictureJson['extra'.$i]) . '" alt="Slide">';
                                    echo '</div>';
                                }
                            }
                            ?>
                    </div>
                   
                    <a class="carousel-control-prev" href="#pictureCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>

                    <a class="carousel-control-next" href="#pictureCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>

                </div>
                <!-- ||||||| -->
            </div>

            <div class="col-sm-8 col-md-6 col-lg-6 offset-sm-0 offset-lg-0">
            <p>
                <?= $currentUser->can('manageBook') ? Html::a('Update', ['update', 'isbn' => $model->isbn], ['class' => 'btn btn-primary']) : '' ?>
                <?= $currentUser->can('manageBook') ? Html::a('Delete', ['delete', 'isbn' => $model->isbn], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) 
                : '';?>
            </p>
                <h1><?=Html::encode($model->title)?></h1>
                <p class="text-secondary"><?=Html::encode($model->author). ", " . Html::encode($model->published)?></p>
                <p class="text-secondary">
                <?php 
                    for($i = 0; $i < count($genres); $i++){
                        if($i==0){
                            echo Html::encode($genres[$i]);
                        }
                        else{
                            echo ", ".Html::encode($genres[$i]);
                        }
                    }               
                    ?>
                </p>
                <p><?=Html::encode($model->description)?></p>
                <p class="text-secondary">
                    <em>ISBN <?= Html::encode($model->isbn)?></em>
                    <span class="badge badge-success">Available: <?= Html::encode($model->available_count) ?></span>
                </p>
            </div>
        </div>
        
    </div>

    <p><?= Html::encode(json_encode($model->pictures)); ?> </p>
    
    <!-- DEBUG <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'isbn',
            'pictures',
            'title',
            'author',
            'published',
            'description:ntext',
            'total_count',
            'available_count',
        ],
    ]) ?> -->

</div>
