<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "book".
 *
 * @property string $isbn
 * @property string|null $pictures
 * @property string $title
 * @property string $author
 * @property string $published
 * @property string $description
 * @property int $total_count
 * @property int $available_count
 *
 * @property BookGenre[] $bookGenres
 * @property LentTo[] $lentTos
 */
class Book extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['isbn', 'title', 'author', 'published', 'description', 'total_count'], 'required'],
            [['pictures', 'description'], 'string'],
            [['published'], 'safe'],
            [['total_count', 'available_count'], 'integer'],
            [['isbn'], 'string', 'max' => 13],
            [['title', 'author'], 'string', 'max' => 255],
            [['isbn'], 'unique'],
            ['pictures','default','value' => NULL]
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'isbn' => 'Isbn',
            'pictures' => 'Pictures',
            'title' => 'Title',
            'author' => 'Author',
            'published' => 'Published',
            'description' => 'Description',
            'total_count' => 'Total Count',
            'available_count' => 'Available Count',
        ];
    }


    /**
     * Gets query for [[BookGenres]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookGenres()
    {
        return $this->hasMany(BookGenre::className(), ['book_isbn' => 'isbn']);
    }

    
    /**
     * Gets query for [[LentTos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLentTos()
    {
        return $this->hasMany(LentTo::className(), ['book_isbn' => 'isbn']);
    }
}
