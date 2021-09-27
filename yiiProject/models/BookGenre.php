<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "book_genre".
 *
 * @property string $book_isbn
 * @property int $genre_id
 *
 * @property Book $bookIsbn
 * @property Genre $genre
 */
class BookGenre extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book_genre';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_isbn', 'genre_id'], 'required'],
            [['genre_id'], 'integer'],
            [['book_isbn'], 'string', 'max' => 13],
            [['book_isbn'], 'exist', 'skipOnError' => true, 'targetClass' => Book::className(), 'targetAttribute' => ['book_isbn' => 'isbn']],
            [['genre_id'], 'exist', 'skipOnError' => true, 'targetClass' => Genre::className(), 'targetAttribute' => ['genre_id' => 'id']],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'book_isbn' => 'Book Isbn',
            'genre_id' => 'Genre ID',
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
     * Gets query for [[Genre]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGenre()
    {
        return $this->hasOne(Genre::className(), ['id' => 'genre_id']);
    }
}
