<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use app\models\Cart;
use app\models\User;
use yii\widgets\Pjax;
?>

<div class="input-form">
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
                    if ($cart == null) echo '';
                    else if ($cart['book'] === []) { // funni gif haha
                        echo '<iframe src="https://giphy.com/embed/6uGhT1O4sxpi8" width="480" height="240" frameBorder="0" class="giphy-embed" allowFullScreen></iframe><p><a href="https://giphy.com/gifs/awkward-pulp-fiction-john-travolta-6uGhT1O4sxpi8"></a></p>';
                    } else {
                        foreach ($cart['book'] as $isbn => $bookInfo) {
                            $element = '';
                            $element .= html::a('<i class="bi bi-x-lg"></i>', '/book/removefromcart?isbn=' . $isbn, ['class' => "btn btn-danger btn-sm"]) . " ";
                            $element .= Html::encode($bookInfo['title']);
                            $element .= ", Amount: ";
                            $element .= Html::encode($bookInfo['amount']);
                            $element .= '<br>';
                            echo $element;
                        }
                    }
                    $cartModel = new Cart;
                    $user = User::findOne($cart['user']['id']);
                    ?>

                    <?php
                    $form = ActiveForm::begin([
                        'action' => [
                            '/book/checkout',
                        ],
                    ]); ?>

                    <br>
                    <?= $form->field($cartModel, 'deadline')->widget(DatePicker::class, [
                        'dateFormat' => 'yyyy-MM-dd',
                        'options' => [
                            'autocomplete' => "off",
                            'placeholder' => date("Y-m-d", strtotime('+30 days')),
                            'id' => 'datePicker',
                        ],
                        'clientOptions' => [
                            'defaultDate' => date("Y-m-d", strtotime('+30 days')),
                            'changeYear' => 'true',
                            'changeMonth' => 'true',
                            'showButtonPanel' => true,
                            'yearRange' => '2000:2040',
                        ],
                    ]) ?>

                </div>

                <?= "<p class='text text-primary text-sm text-center'>User note: ". Html::encode($user->note) ."</p>"?>
                <div class="modal-footer">
                    <div class="form-group ml-3">
                        <?= Html::submitButton('Checkout', ['class' => 'btn btn-primary',]) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>