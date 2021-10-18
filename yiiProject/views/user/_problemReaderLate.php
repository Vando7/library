<?php

use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\LentToSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<?php Pjax::begin();?>
<h4>List of users and the most they are late by on returning a book.</h4>
<?= GridView::widget([
    'dataProvider' => $dataProviderLate,
    'columns' => [
        [
            'format' => 'raw',
            'label'  => 'Reader',
            'value'  => function ($model) {
                $element = '';
                // User names
                $user = $model->user;
                $element .=  '<i class="bi bi-person"></i> ' . Html::a(Html::encode($user->first_name . " " . $user->last_name), "/user/view?id=" . $user->id) . "<br>";

                // User Phone
                $element .= '<i class="bi bi-telephone"></i> ' . Html::encode($user->phone) . "<br>";

                // User Email
                $element .= '<i class="bi bi-envelope"></i> ' . Html::encode($user->email) . "<br>";

                return $element;
            }
        ],
        [
            'format' => 'raw',
            'label'  => 'Late by',
            'value'  => function ($model) {
                $element = '';
                $element .= '<h3>';
                $deadline = new DateTime($model->deadline);
                $today = new DateTime();
                
                $days = $deadline->diff($today)->days;
                if($days >= 365){
                    $years = '';
                    $years .= floor($days/365);
                    $element .= $years;
                    if($years == 1){$element .= ' year ';}
                    else $element .= ' years ';
                }

                if($days%365 >= 31){
                    $months = '';
                    $months = floor(($days%365)/31);
                    $element .= $months;
                    if($months == 1 ){ $element .= ' month ';}
                    else $element .= ' months ';
                }
                else {
                    $element .= $days%365 . ' days';
                }

                $element .= '</h3>';
                return $element;
            }
        ],
    ],
]);
?>
<?php 
    $fixPagination = <<< JS
    $(function(){
            $("ul.pagination > li > a").addClass("page-link");
        });
    JS;
    $this->registerJs($fixPagination);
    Pjax::end();
?>