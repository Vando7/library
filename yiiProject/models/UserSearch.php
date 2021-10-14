<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * Global Search string
     * @var globalSearch
     */
    public $globalSearch = "";

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['first_name', 'last_name', 'country', 'city', 'street', 'phone', 'email', 'password', 'role', 'note', 'register_date', 'suspended_status', 'suspended_date', 'suspended_reason', 'globalSearch'], 'safe'],
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
        $query = User::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'register_date' => $this->register_date,
            'suspended_date' => $this->suspended_date,
        ]);

        $words = explode(" ", $this->globalSearch);
        $query->orFilterWhere([
            'or',
            ['like', 'first_name',  $words],
            ['like', 'last_name',   $words],
            ['like', 'email',   $words],
            ['like', 'phone',   $words],
        ]);

        return $dataProvider;
    }
}
