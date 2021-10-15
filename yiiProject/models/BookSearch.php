<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Book;
use yii\helpers\VarDumper;

/**
 * BookSearch represents the model behind the search form of `app\models\Book`.
 */
class BookSearch extends Book
{
    /**
     * @var globalSearch
     */
    public $globalSearch = "";


    /**
     * @var genreSearch
     */
    public $genreSearch = [];


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['isbn', 'pictures', 'title', 'author', 'published', 'description', 'globalSearch', 'genreSearch', 'genre.id'], 'safe'],
            [['total_count', 'available_count'], 'integer'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Book::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => ['defaultOrder' => ['pictures' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        if ($this->genreSearch) {
            $query->select(['`book`.* ,COUNT(*)']);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'published' => $this->published,
            'total_count' => $this->total_count,
            'available_count' => $this->available_count,
        ]);

        if ($this->genreSearch) {
            $query->joinWith('genres AS genre')
                ->where(['genre.id' => $this->genreSearch])
                ->GroupBy('book.isbn')
                ->having(['=', "COUNT(*)", count($this->genreSearch)]); // this is fine ignore the warning
        } else {
            $query->joinWith('genres AS genre')
                ->groupBy('book.isbn');
        }

        $words = explode(' ', $this->globalSearch);

        $query
            ->orFilterWhere(['like', 'title',   $words],)
            ->orFilterWhere(['like', 'author',  $words])
            ->orFilterWhere(['like', 'isbn',    $words],)
            ->orFilterWhere(['like', 'published', $words],);


        return $dataProvider;
    }
}
