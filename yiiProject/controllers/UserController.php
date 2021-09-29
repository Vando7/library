<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SignupForm;
use yii\helpers\Url;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    public function beforeAction($action) {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (Yii::$app->user->isGuest  
            && !($this->action->id == 'login') 
            && !($this->action->id == 'signup')) 
        {
            return $this->redirect(['login']);
        }

        return true;
    }


    public function beforeSave() {
        foreach ($this->attributes as $key => $value)
            if (!$value){
                $this->$key = NULL;
            }
    
        return parent::beforeSave();
    }
    

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $currentUser = \Yii::$app->user;
        
        if($currentUser->can('viewAllProfilesLibrary')){
            $searchModel = new UserSearch();
            $dataProvider = $searchModel->search($this->request->queryParams);
    
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }  
        else return $this->actionView($currentUser->identity->id);
    }


    /**
     * Displays a single User model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    { 
        $currentUser = Yii::$app->user->identity;

        if($currentUser->role == 'reader'){
            return $this->render('view', [
                'model' => $this->findModel($currentUser ->id),
            ]);
        } else{
            return $this->render('viewLibrarian', [
                'model' => $this->findModel($id),
            ]);
        }
    }


    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(Yii::$app->user->identity->role != 'admin'){
            return $this->goHome();
        }

        $model = new User();
    
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }
    
        return $this->render('create', [
                'model' => $model,
            ]);
    }


    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        // Reader and librarian can only update own profiles.
        $currentUser = Yii::$app->user->identity;

        if($currentUser->role == 'reader'){
            $model = $this->findModel($currentUser->id);
        } else {
            $model = $this->findModel($id);
        }

        // Process Request
        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // Reader and librarian can only update own profiles.
        $currentUser = Yii::$app->user->identity;

        if($currentUser->role == 'admin' ){
            $this->findModel($id)->delete();
        }

        return $this->redirect(['index']);
    }


    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['book']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->setHomeUrl(Url::to(['/book']));
            return $this->goHome();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }


    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->setHomeUrl(Url::to(['/user/login']));
        Yii::$app->user->logout();

        return $this->goHome();
    }

    
    public function actionSignup(){
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['book']);
        }

        $model = new SignupForm();
       
        if($model->load(Yii::$app->request->post()) && $model->signup()){
            return $this->redirect(Yii::$app->homeUrl);
        }

        return $this->render("signup",['model'=>$model]);
    }
}
