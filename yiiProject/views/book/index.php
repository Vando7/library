<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\bootstrap4\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Books';
$currentUser = Yii::$app->user->identity;

?>
<div class="book-index">
    <p>
        <?= $currentUser->role == 'reader' ? '' : Html::a('Create Book', ['create'], ['class' => 'btn btn-success']) ?>
        <?= $currentUser->role == 'reader' ? '' : Html::Button('Manage Genres', [
            'value' => Url::to('/book/viewgenre'), 
            'class' => 'btn btn-success', 
            'id'    => 'genreModalButton',
            ])?>

        <?php $modal = Modal::begin([
            'title' => 'Manage genres', 
            'id' => 'genreModal',
        ]); 
        
        echo '<div id="genreModalContent"></div>';
        Modal::end();
        ?>
    </p>

    <?php echo $this->render('_search', [
        'model' => $searchModel, 
        'genreList' => $genreList,
        ]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'format' => 'raw',
                'label' => "Cover",
                'value' => function($model){
                    $pictureJson = json_decode($model->pictures,true);
                    if($pictureJson){
                        $element = '';
                        $element .= '<a href="/book/view?isbn='.$model->isbn.'" alt="book cover">';
                        $element .= Html::img(
                            '/'. Html::encode($pictureJson['cover']),[
                            //'width'=>'100px',
                            'style' => [
                                'max-width' => '130px'
                            ],
                        ],
                        );
                        $element .= '</a>';
                        return $element;
                    }
                }
            ],
            [
                'format' => 'html',
                'label' => 'Info',
                'value' => function($model){
                    $publishYear = DateTime::createFromFormat("Y-m-d",$model->published);
                    $publishYear = $publishYear->format('Y');
                    
                    $elements = '';
                    $elements .= '<b>' . Html::a(Html::encode($model->title),'view?isbn='.$model->isbn) . '</b> ('.$publishYear.')<br>';
                    $elements .= '<i>' . Html::encode($model->author) . '</i>' .'<br>';

                    for($i = 0; $i < count($model->genres); ++$i){
                        if($i == 0) $elements .= $model->genres[$i]->name;
                        else        $elements .= ", ".$model->genres[$i]->name;
                    }

                    $elements .= '<br>';
                    $elements .= '<span class="badge badge-success">Available:' . Html::encode($model->available_count) . '</span><br>';
                    $elements .= "ISBN6 " . Html::a(Html::encode($model->isbn),'view?isbn='.$model->isbn).'<br>';
                    
                    return $elements;
                    return DetailView::widget([
                        'model' => $model,
                        'attributes' => 
                        [
                            'isbn',
                            'title',
                            'author',
                            'published',
                        ],
                    ]);
                }
            ],
            //'title',
            //'author',
            //'published',
            //'description:ntext',
            //'total_count',
            //'available_count',
            ['class' => 'yii\grid\ActionColumn',
             'urlCreator' => function($action,$model){
                 if($action == 'update'){
                     return 'update?isbn='.$model->isbn;
                 }

                 if($action == 'delete'){
                     return 'delete?isbn='.$model->isbn;
                 }
             },
             'visible' => Yii::$app->user->can('manageBook'),
            ]
            
        ],
    ]); ?>


</div>
