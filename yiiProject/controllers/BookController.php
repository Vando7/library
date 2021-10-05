<?php

namespace app\controllers;

use Yii;
use app\models\Book;
use app\models\Genre;
use app\models\BookSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
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
        $genreList  = $genreDB->find()->all();
        $newGenre   = New Genre;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'genreList' => $genreList,
            'newGenre' => $newGenre,
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
     */
    public function uploadPictures($model){
        $model->bookCover   = UploadedFile::getInstance($model,'bookCover');
        $model->bonusImages = UploadedFile::getInstances($model,'bonusImages');

        $counter = 0;
        $picturesJson = json_decode($model->pictures, true);

        if($model->bookCover){
            if(array_key_exists('cover', $picturesJson)){
                $picturesJson['cover'] = 'upload/'. $model->isbn .'_cover.'.$model->bookCover->extension;
            } else {
                $picturesJson += ['cover' => 'upload/'. $model->isbn .'_cover.'.$model->bookCover->extension];
            }
        }

        if($model->bonusImages){
            $counter = 1;
            foreach ($model->bonusImages as $files){
                if(array_key_exists('cover', $picturesJson)){
                    $picturesJson['extra'.$counter] = 'upload/'. $model->isbn . '_extra' . $counter . '.'  . $files->extension;
                } else{
                    $picturesJson += ['extra'.$counter => 'upload/'. $model->isbn . '_extra' . $counter . '.'  . $files->extension];
                }
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
                    return $this->redirect(['view', 'isbn' => $model->isbn]);
                }
            } else {
                $model->loadDefaultValues();
            }
    
            $genreDB    = New Genre;
            $genreList  = $genreDB->find()->all();
            $newGenre   = New Genre;

            return $this->render('create', [
                'model'     => $model,
                'genreList' => $genreList,
                'newGenre'  => $newGenre,
            ]);
        }
        else return $this->actionIndex();
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

                return $this->redirect(['view', 'isbn' => $model->isbn]);
            }
            
            $genreDB    = New Genre;
            $genreList  = $genreDB->find()->all();
            $newGenre   = New Genre;

            return $this->render('update', [
                'model'     => $model,
                'genreList' => $genreList,
                'newGenre'  => $newGenre,
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

        $genreDB    = New Genre;
        $genreList  = $genreDB->find()->all();
        $newGenre   = New Genre;

        return $this->render('viewGenre', [
            'genreList' => $genreList,
            'newGenre'  => $newGenre,
        ]);
    }


    public function actionDeletegenre($id){
        if(Yii::$app->user->can('manageBook')){
            $this->findGenre($id)->delete();
            $genreDB    = New Genre;
            $genreList  = $genreDB->find()->all();
            $newGenre   = New Genre;

            return $this->render('viewGenre', [
                'genreList' => $genreList,
                'newGenre'  => $newGenre,

                ]);
        }
        
    }


    public function actionViewgenre(){
        $genreDB    = New Genre;
        $genreList  = $genreDB->find()->all();
        $newGenre   = New Genre;

        return $this->renderAjax('viewGenre', [
            'genreList' => $genreList,
            'newGenre'  => $newGenre,
        ]);
    }

}
