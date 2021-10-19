<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Book */

$this->title = $model->title;
$this->registerCssFile("/css/bookView.css");
$currentUser = Yii::$app->user;
$pictureJson = json_decode($model->pictures, true);

\yii\web\YiiAsset::register($this);
?>

<div class="book-view" style="margin-top: 50px;">
    <div class="container">
        <div class="row">
            <!-- Carousel -->

            <div class="col-sm-4 col-md-6 col-lg-5" id="carouselColumn">
                <div id="pictureCarousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <?= $pictureJson ?
                                '<img class="w-100 d-block" src="/' . Html::encode($pictureJson['cover']) . '"alt="First slide">'
                                : ''
                            ?>
                        </div>

                        <?php
                        if ($pictureJson) {
                            for ($i = 1; $i < count($pictureJson); $i++) {
                                echo '<div class="carousel-item">';
                                echo '<img class="w-100 d-block" src="/' . Html::encode($pictureJson['extra' . $i]) . '" alt="Slide">';
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
            </div>

            <!-- Book info -->

            <div class="col-sm-8 col-md-6 col-lg-6 offset-sm-0 offset-lg-0">
                <p>
                    <?= $currentUser->can('manageBook') ?
                        Html::a('<i class="bi bi-pencil-square"></i> Update', ['update', 'isbn' => $model->isbn], ['class' => 'btn btn-primary'])
                        : ''
                    ?>

                    <?= $currentUser->can('manageBook') ? Html::a('<i class="bi bi-trash"></i> Delete', ['delete', 'isbn' => $model->isbn], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ])
                        : ''; ?>
                </p>
                <hr>
                <h1><?= Html::encode($model->title) ?></h1>
                <p class="text-secondary"><?= Html::encode($model->author) . ", published " . (date("F jS, Y", strtotime($model->published))) ?></p>
                <p class="text-secondary"><i>
                    <?php
                    for ($i = 0; $i < count($genres); $i++) {
                        if ($i == 0) {
                            echo Html::encode($genres[$i]);
                        } else {
                            echo ", " . Html::encode($genres[$i]);
                        }
                    }
                    ?>
                </i></p>
                
                <h3> Description </h3>
                <p class="text-justify"><?= Html::encode($model->description) ?></p>
                <p class="text-secondary">
                    <em>ISBN <?= Html::encode($model->isbn) ?></em>
                    <?php
                    if ($model->available_count > 20) {
                        echo '<span class="badge badge-success">Available:' . Html::encode($model->available_count) . '</span>';
                    } else if ($model->available_count <= 20 && $model->available_count > 0) {
                        echo '<span class="badge badge-warning">Available:' . Html::encode($model->available_count) . '</span>';
                    } else {
                        echo '<span class="badge badge-danger">Not available.</span>';
                    }
                    ?>
                </p>
                <!-- Reserve button -->
                <hr>
                <?php
                if($currentUser->identity->suspended_status === 'yes'){
                    if($reserveButton == ''){
                        echo Html::a('<i class="bi bi-x-lg"></i> Cancel reservation', [
                                '/user/cancelreserved', 
                                'isbn' => $model->isbn,
                                'userID'   => $currentUser->identity->id,
                            ], 
                            ['class' => 'btn btn-danger']);
                    }
                    else echo Html::a('Account suspended', '#', ['class' => 'btn btn-danger disabled', 'aria-disabled' => 'true']);
                }
                else if ($model->available_count <= 0) {
                    echo '';
                } else if ($reserveButton !== '') {
                    echo Html::a('<i class="bi bi-save"></i> Reserve', ['reservebook', 'isbn' => $model->isbn], ['class' => 'btn btn-success']);
                } else {
                    echo Html::a('<i class="bi bi-x-lg"></i> Cancel reservation', [
                        '/user/cancelreserved', 
                        'isbn' => $model->isbn,
                        'userID'   => $currentUser->identity->id,
                    ], 
                    ['class' => 'btn btn-danger']);
                }
                ?>
            </div>
        </div> 
        <div class="container"  style="margin:auto;margin-top:50px;max-width:850px;",>
        <?= $currentUser->can('manageBook') ? '<hr><h2>Lend history of this book</h2>' : ''; ?>
        
            <?= $currentUser->can('manageBook') == false ? '' : GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'format' => 'raw',
                        'label'  => 'Book info',
                        'value'  => function ($model) {
                            // Book name
                            $element  = '';
                            $element .= '<b><i class="bi bi-book"></i> ';
                            $element .= Html::encode($model->bookIsbn->title);
                            $element .= '</b><br>';

                            // Amount
                            $element .= '<i>';
                            $element .= Html::encode($model->amount) . " " . ($model->amount > 1 ? " Copies" : " Copy") . " ";
                            $element .= '</i>';

                            // Status
                            $badgeType = '';
                            $status    = $model->status;
                            if ($model->status == 'returned') $badgeType = 'success';
                            if ($model->status == 'reserved') $badgeType = 'warning';
                            if ($model->status == 'taken') {
                                $deadline = strtotime($model->deadline);
                                $today    = strtotime('now');

                                if ($deadline < $today) {
                                    $badgeType = 'danger';
                                    $status = 'Past Deadline';
                                } else {
                                    $badgeType = 'primary';
                                    $status = 'Not Returned';
                                }
                            }
                            $element .= '<span class="badge badge-' . $badgeType . '">' . $status . '</span><br>';

                            // Employee info
                            $element .= "Given by <br>";
                            $element .= Html::encode($model->employee->first_name . " " . $model->employee->last_name);

                            return $element;
                        }
                    ],
                    [
                        'format' => 'raw',
                        'label'  => 'Given To',
                        'value'  => function ($model) {
                            $element = '';

                            // User names
                            $user = $model->user;
                            $element .=  '<i class="bi bi-person"></i> ' . Html::a(Html::encode($user->first_name . " " . $user->last_name), "/user/view?id=" . $user->id) . "<br>";

                            // User Phone
                            $element .= '<i class="bi bi-telephone"></i> ' . Html::encode($user->phone) . "<br>";

                            // Date lent
                            if($model->status == 'reserved'){
                                $element .= "<b>Take book:</b> Today<br>";
        
                                return $element;
                            }
        
                            $element .= '<b>Given</b> ' . Html::encode(date("F jS, Y", strtotime($model->date_lent))) . "<br>";
        
                            // Date returned 
                            if ($model->status == 'returned') {
                                $element .= "<b>Returned</b> " . ($model->date_returned ? date("F jS, Y", strtotime($model->date_returned)) : "No") . '<br>';
                            }
        
                            // Deadline 
                            $element .= "<b>Deadline</b> " . (date("F jS, Y", strtotime($model->deadline))) . "<br>";

                            return $element;
                        }
                    ],
                ],
            ]);
            ?>
        </div>
        <?php 
    $script = <<< JS
            $(function(){
                    $("ul.pagination > li > a").addClass("page-link");
                });
        JS;
        $this->registerJs($script);
        ?>
    </div>
</div>