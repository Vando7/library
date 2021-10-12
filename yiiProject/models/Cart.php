<?php

namespace app\models;
use yii\base\Model;

class Cart extends Model
{
    public $amount;
    public $deadline;

    
    public function rules(){
        return [
            ['amount', 'integer', 'min' => 0],
            ['deadline', 'datetime', 'format' => 'php:Y-m-d H:i:s'],
        ];
    }


    public function checkout($books){
        return true;
    }
}

?>