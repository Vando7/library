<?php

namespace app\controllers;

use Yii;
use app\models\Book;
use app\models\BookSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

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


    public function init(){
        parent::init();
        /*
        if (Yii::app()->urlManager->showScriptName == false){
            if (strpos(Yii::app()->request->requestUri, '/index.php') !== false){
                $_uri = str_replace("/index.php", "", Yii::app()->request->requestUri);
                $_uri = str_replace("//", "", $_uri);
                $this->redirect($_uri);
            }
        }
        */
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
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
        return $this->render('view', [
            'model' => $this->findModel($isbn),
        ]);
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
                if ($model->load($this->request->post()) && $model->save()) {
                    return $this->redirect(['view', 'isbn' => $model->isbn]);
                }
            } else {
                $model->loadDefaultValues();
            }
    
            return $this->render('create', [
                'model' => $model,
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
            
            if ($this->request->isPost && $model->load($this->request->post()) ) {
                $model->bookCover = UploadedFile::getInstance($model,'bookCover');
                $model->bonusImages = UploadedFile::getInstances($model,'bonusImages');
                $model->save();
                $model->upload();
                $model->pictures = $model->allImagesJson;

                return $this->redirect(['view', 'isbn' => $model->isbn]);
            }
            
            return $this->render('update', [
                'model' => $model,
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

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
