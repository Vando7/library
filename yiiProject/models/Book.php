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
     * @var bookCover
     */
    public $bookCover;


    /**
     * @var bonusImages[]
     */
    public $bonusImages;


    /**
     * @var genreList[]
     */
    public $genreList = [];


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['isbn', 'title', 'author', 'published', 'description', 'total_count'], 'required'],
            [['pictures', 'description'], 'string'],
            [['published'], 'date', 'format' => 'php:Y-m-d'],
            [['total_count', 'available_count'], 'integer'],
            [['isbn'], 'string', 'max' => 20],
            [['title', 'author'], 'string', 'max' => 255],
            [['isbn'], 'unique'],
            ['pictures', 'default', 'value' => null],
            [['bookCover'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 2097150],
            [['bonusImages'], 'file', 'skipOnEmpty' => true,  'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10],
            [['genreList'], 'safe'],
        ];
    }


    /**
     * Deprecated function
     */
    public function upload()
    {
        if ($this->validate()) {

            if ($this->bookCover) {
                // $this->bookCover->saveAs('upload/' . $this->isbn . '_cover.' . $this->bookCover->extension);
            }

            $pictureJson = json_decode($this->pictures,true);

            $counter = 1;
            while(array_key_exists('extra'.$counter, $pictureJson)){
                $counter++;
            }

            if ($this->bonusImages) {
                foreach ($this->bonusImages as $files) {
                    //$files->saveAs('upload/' . $this->isbn . '_extra' . $counter . '.' . $files->extension);
                    $counter++;
                }
            }
            return true;
        }

        return false;
    }


    // Very rare, but heavy function.
    public function normalizePictures(){
        if(!$this->pictures)
            return true;

        $picJson = json_decode($this->pictures, true);

        $counter = 1;

        while(array_key_exists('extra'.$counter, $picJson) && file_exists($picJson['extra'.$counter])){
            $ext = pathinfo($picJson['extra'.$counter], PATHINFO_EXTENSION);
            rename($picJson['extra'.$counter],'upload/'.$counter.'.'.$ext);
            $picJson['extra'.$counter] = 'upload/'.$counter.'.'.$ext;
            
            ++$counter;
        }

        $counter = 1;
        while(array_key_exists('extra'.$counter, $picJson) && file_exists($picJson['extra'.$counter])){
            $ext = pathinfo($picJson['extra'.$counter], PATHINFO_EXTENSION);
            rename($picJson['extra'.$counter], 'upload/' . $this->isbn . '_extra' . $counter . '.'  . $ext);
            $picJson['extra'.$counter] = 'upload/' . $this->isbn . '_extra' . $counter . '.'  . $ext;
            
            $counter++;
        }

        $this->pictures = json_encode($picJson);
        return true;
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

    public function getGenres()
    {
        return $this->hasMany(Genre::class, ['id' => 'genre_id'])
            ->viaTable('book_genre', ['book_isbn' => 'isbn']);
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
