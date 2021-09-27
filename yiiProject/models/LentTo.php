<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lent_to".
 *
 * @property string $book_isbn
 * @property int $user_id
 * @property int $employee_id
 * @property int $amount
 * @property string $date_lent
 * @property string|null $date_returned
 * @property string $deadline
 * @property string $status
 *
 * @property Book $bookIsbn
 * @property User $employee
 * @property User $user
 */
class LentTo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lent_to';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_isbn', 'user_id', 'employee_id', 'amount', 'deadline', 'status'], 'required'],
            [['user_id', 'employee_id', 'amount'], 'integer'],
            [['date_lent', 'date_returned', 'deadline'], 'safe'],
            [['status'], 'string'],
            [['book_isbn'], 'string', 'max' => 13],
            [['book_isbn'], 'exist', 'skipOnError' => true, 'targetClass' => Book::className(), 'targetAttribute' => ['book_isbn' => 'isbn']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['employee_id' => 'id']],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'book_isbn' => 'Book Isbn',
            'user_id' => 'User ID',
            'employee_id' => 'Employee ID',
            'amount' => 'Amount',
            'date_lent' => 'Date Lent',
            'date_returned' => 'Date Returned',
            'deadline' => 'Deadline',
            'status' => 'Status',
        ];
    }


    /**
     * Gets query for [[BookIsbn]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookIsbn()
    {
        return $this->hasOne(Book::className(), ['isbn' => 'book_isbn']);
    }


    /**
     * Gets query for [[Employee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(User::className(), ['id' => 'employee_id']);
    }

    
    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
