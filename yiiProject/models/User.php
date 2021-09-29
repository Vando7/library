<?php

namespace app\models;
use yii\web\IdentityInterface;


use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $country
 * @property string $city
 * @property string $street
 * @property string $phone
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string|null $note
 * @property string $register_date
 * @property string|null $suspended_status
 * @property string|null $suspended_date
 * @property string|null $suspended_reason
 *
 * @property LentTo[] $lentTos
 * @property LentTo[] $lentTos0
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $newPassword;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'country', 'city', 'street', 'phone', 'email', 'password'], 'required'],
            [['role', 'note', 'suspended_status', 'suspended_reason'], 'string'],
            [['register_date', 'suspended_date'], 'safe'],
            [['first_name', 'last_name', 'country', 'city', 'street'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 20],
            [['email', 'password'], 'string', 'max' => 255],
            [['phone'], 'unique'],
            [['email'], 'unique'],
            ['newPassword','string'],
            ['suspended_status','default','value' => NULL],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'country' => 'Country',
            'city' => 'City',
            'street' => 'Street',
            'phone' => 'Phone',
            'email' => 'Email',
            'password' => 'Password',
            'role' => 'Role',
            'note' => 'Note',
            'register_date' => 'Register Date',
            'suspended_status' => 'Suspended Status',
            'suspended_date' => 'Suspended Date',
            'suspended_reason' => 'Suspended Reason',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
        ];
    }


    /**
     * Gets query for [[LentTos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLentTos()
    {
        return $this->hasMany(LentTo::className(), ['user_id' => 'id']);
    }


    /**
     * Gets query for [[LentTos0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLentTos0()
    {
        return $this->hasMany(LentTo::className(), ['employee_id' => 'id']);
    }


    public static function findByEmail($email){
        return self::findOne(['email'=>$email]);
    }


    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['access_token' => $token]);
    }


    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($email)
    {
        return self::findOne(['email' => $email]);
    }


    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }


    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($auth_key)
    {
        return $this->auth_key === $auth_key;
    }


    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password,$this->password);
    }


    public function setPassword($password){
        $this->password = \Yii::$app->security->generatePasswordHash($password);
    }


     /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }

    public function afterSave($id,$newRole){
        /* ASSIGN ROLE CHANGE.
        $auth = \Yii::$app->authManager;
        $userRole = $auth->getRole($newRole);

        $user= self::findIdentity($id);
        $user->role = $newRole;
        $user->update();

        $auth->assign($userRole, $id);
        return true;*/
    }
}
