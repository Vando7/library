<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
?>

<div class="input-form">
    <?php Pjax::begin();?>

    <div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Finalize</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <div class="modal-body">
            <?php
            $session = Yii::$app->session;
            $cart = $session->has('cart') ? $session->get('cart') : null;
            if($cart['book'] === []){
                echo '<iframe src="https://giphy.com/embed/6uGhT1O4sxpi8" width="480" height="240" frameBorder="0" class="giphy-embed" allowFullScreen></iframe><p><a href="https://giphy.com/gifs/awkward-pulp-fiction-john-travolta-6uGhT1O4sxpi8"></a></p>';
            }
            else {
                foreach($cart['book'] as $isbn=>$bookInfo){
                    echo Html::encode($bookInfo['title']);
                    echo '<br>';
                }
            }
            ?>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <?php Pjax::end();?>
</div>