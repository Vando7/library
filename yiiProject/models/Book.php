<?php

namespace app\models;

use Yii;
use yii\helpers\Json;

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


    public $allImagesJson;

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
            ['pictures','default','value' => NULL],
            [['bookCover'],'file','skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['bonusImages'], 'file', 'skipOnEmpty' => false,  'extensions' => 'png, jpg', 'maxFiles' => 10],
        ];
    }

    public function upload(){
        if($this->validate()){
            $newPictures = [];

            if($this->bookCover){
                $this->bookCover->saveAs('upload/'. $this->isbn .'_cover.'.$this->bookCover->extension);
                $newPictures += ['cover' => 'upload/'. $this->isbn .'_cover.'.$this->bookCover->extension];
            }

            if($this->bonusImages){
                $counter = 1;
                foreach ($this->bonusImages as $files){
                    $files->saveAs('upload/'. $this->isbn . '_extra' . $counter . '.' . $files->extension);
                    $newPictures += ['extra'.$counter => 'upload/'. $this->isbn .'_cover.'.$this->bookCover->extension];
                    ++$counter;
                }
            }

            if(!empty($newPictures)){
                $allImagesJson = Json::encode($newPictures);
                error_log($allImagesJson,3,'ivan_log.txt');
            }
            //TODO: MAKE/UPDATE JSON HERE
            return true;
        }

        return false;
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
