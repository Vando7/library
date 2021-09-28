<?php


namespace app\models;
use yii\base\Model;
use yii\helpers\VarDumper;


class SignupForm extends Model{
    public $first_name;
    public $last_name;
    public $country;
    public $city;
    public $street;
    public $email;
    public $phone;
    public $password;
    public $password_repeat;

    
    public function rules(){
        return [
            [['first_name', 'last_name', 'country', 'city', 'street', 'phone', 'email', 'password'], 'required'],
            [['role', 'note', 'suspended_status', 'suspended_reason'], 'string'],
            [['register_date', 'suspended_date'], 'safe'],
            [['first_name', 'last_name', 'country', 'city', 'street'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 20],
            [['email', 'password'], 'string', 'max' => 255],
            [['phone'], 'unique'],
            [['email'], 'unique', 'message' => 'Account with this e-mail already exists.'],
            ['email', 'email', 'message' => 'Please enter a valid e-mail address'],
            ['password_repeat','compare','compareAttribute'=>'password'],
        ];
    }


    public function signup(){
        $user = new User();
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->country = $this->country;
        $user->city = $this->city;
        $user->street = $this->street;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->password = \Yii::$app->security->generatePasswordHash($this->password);
        $user->access_token=\Yii::$app->security->generateRandomString();
        $user->auth_key=\Yii::$app->security->generateRandomString();

        if($user->save()){
            $auth = \Yii::$app->authManager;
            $readerRole = $auth->getRole('reader');
            $auth->assign($readerRole,$user->getId());
            return true;
        }

        \Yii::error("User was not saved".VarDumper::dumpasString($user->errors));
        return false;
    }
}