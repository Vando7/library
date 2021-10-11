<?php

namespace app\controllers;

use Yii;
use app\models\Book;
use app\models\Genre;
use app\models\BookGenre;
use app\models\BookSearch;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\helpers\Json;
use yii\helpers\VarDumper;


/**
 * BookController implements the CRUD actions for Book model.
 */
class BookController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }


    public function beforeAction($action) {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (Yii::$app->user->isGuest  
            && !($this->action->id == 'login') 
            && !($this->action->id == 'signup')) 
        {
            return $this->goHome();
        }

        return true;
    }
    

    /**
     * Lists all Book models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel    = new BookSearch();
        $dataProvider   = $searchModel->search($this->request->queryParams);

        $genreDB    = New Genre;

        return $this->render('index', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
            'pageSize'      => 10,
            'genreList'     => $this->getGenreNames(),
        ]);
    }


    /**
     * Displays a single Book model.
     * @param string $isbn Isbn
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($isbn)
    {
        $model = $this->findModel($isbn);

        $genresQuery = $model->genres;
        $genres = [];

        foreach($genresQuery as $genreObject){
            array_push($genres,$genreObject->name);
        }

        return $this->render('view', [
            'model' => $model,
            'genres' => $genres,
        ]);
    }
    

    /**
     * Uploads pictures specified in the create form.
     * - maybe  can be moved to book model.
     */
    public function uploadPictures($model){
        $model->bookCover   = UploadedFile::getInstance($model,'bookCover');
        $model->bonusImages = UploadedFile::getInstances($model,'bonusImages');

        $counter = 0;
        $picturesJson = [];
        $picturesJson = json_decode($model->pictures, true);

        if($model->bookCover){
            $picturesJson['cover'] = 'upload/'. $model->isbn .'_cover.'.$model->bookCover->extension;
        }

        $counter = 1;
        if($model->bonusImages){
            foreach ($model->bonusImages as $files){
                $picturesJson['extra'.$counter] = 'upload/'. $model->isbn . '_extra' . $counter . '.'  . $files->extension;

                ++$counter;
            }

            while( file_exists('upload/'. $model->isbn . '_extra' . $counter ) ){
                unlink('upload/'. $model->isbn . '_extra' . $counter);

                ++$counter;
            }
        }
        
        $model->pictures = json_encode($picturesJson);
        return $model->save() && $model->upload();
    }


    /**
     * Creates a new Book model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->can('manageBook')){
            $model = new Book();
    
            if ($this->request->isPost) {
                if ($model->load($this->request->post()) && $model->save() ) {

                    $this->uploadPictures($model); 
                    $this->saveBookGenres($model);

                    return $this->redirect(['view', 'isbn' => $model->isbn]);
                }
            } else {
                $model->loadDefaultValues();
            }

            return $this->render('create', [
                'model'     => $model,
                'genreList' => $this->getGenreNames(),
            ]);
        }
        else return $this->actionIndex();
    }


    /**
     * Returns a list of all book genres in the following format:
     * [ genre_id => genre_name ]
     */
    public function getGenreNames(){
        $genreDB    = New Genre;
        $genreListRaw  = $genreDB->find()->all();
        $genreList = [];

        foreach ($genreListRaw as $genreObj){
            $genreList += [$genreObj->id => $genreObj->name];
        }

        return $genreList;
    }


    /**
     * Removes previous genres the book had and writes the
     * ones currently written in $model->genreList
     */
    public function saveBookGenres($model ){
        $bookGenreList = $model->genres;

        foreach($bookGenreList as $bookGenreObj){
            $model->unlink('genres',$bookGenreObj,true);
        }

        foreach($model->genreList as $genreObj){
            $newGenre = New BookGenre;
            $newGenre->genre_id = $genreObj;
            $newGenre->link('bookIsbn',$model);
        }

        $model->genreList = null;

        return true;
    }


    /**
     * Updates an existing Book model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $isbn Isbn
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($isbn)
    {
        $model = $this->findModel($isbn);
        
        if(Yii::$app->user->can('manageBook')){
            if ($this->request->isPost && $model->load($this->request->post())) {
                
                $this->uploadPictures($model);
                $this->saveBookGenres($model);

                return $this->redirect(['view', 'isbn' => $model->isbn]);
            }

            $genresQuery = $model->genres;

            foreach($genresQuery as $genreObj){
                array_push($model->genreList, $genreObj->id);
            }

            return $this->render('update', [
                'model'     => $model,
                'genreList' => $this->getGenreNames(),
            ]);
        }
        else
            return $this->redirect(['view', 'isbn' => $model->isbn]);
    }


    /**
     * Deletes an existing Book model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $isbn Isbn
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($isbn)
    {
        if(Yii::$app->user->can('manageBook')){
            $this->findModel($isbn)->delete();
        }
        return $this->redirect(['index']);
    }


    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $isbn Isbn
     * @return Book the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($isbn)
    {
        if (($model = Book::findOne($isbn)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested book does not exist.');
    }


    protected function findGenre($id){
        if(($model = Genre::findOne($id)) !== null){
            return $model;
        }
        throw new NotFoundHttpException('The requested genre does not exist.');
    }


    public function actionCreategenre(){
        if(Yii::$app->user->can('manageBook')){
            $model = new Genre;

            $model->load(Yii::$app->request->post());
            $model->save();
        }

        $newGenre   = New Genre;
        $genreListRaw  = $newGenre->find()->all();

        return $this->renderAjax('viewGenre', [
            'genreList' => $genreListRaw,
            'newGenre'  => $newGenre,
        ]);
    }


    public function actionDeletegenre($id){
        if(Yii::$app->user->can('manageBook')){
            $this->findGenre($id)->delete();

            $newGenre   = New Genre;
            $genreListRaw  = $newGenre->find()->all();

            return $this->renderAjax('viewGenre', [
                'genreList' => $genreListRaw,
                'newGenre'  => $newGenre,
                ]);
        }
    }


    public function actionViewgenre($isModal = true){
        $newGenre   = New Genre;
        $genreListRaw  = $newGenre->find()->all();

        if($isModal){
            return $this->renderAjax('viewGenre', [
                'genreList' => $genreListRaw,
                'newGenre'  => $newGenre,
            ]);
        }

        return $this->render('viewGenre', [
            'genreList' => $genreListRaw,
            'newGenre'  => $newGenre,
        ]);
    }

}
