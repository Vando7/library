<?php

namespace app\controllers;

use Yii;

use app\models\Book;
use app\models\User;
use app\models\LoginForm;
use app\models\UserSearch;
use app\models\SignupForm;
use app\models\LentTo;
use app\models\LentToSearch;

use yii\web\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use yii\bootstrap4\ActiveForm;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\helpers\Url;
use yii\helpers\VarDumper;

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


    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (
            Yii::$app->user->isGuest
            && !($this->action->id == 'login')
            && !($this->action->id == 'signup')
        ) {
            return $this->redirect(['login'])->send();
        }

        return true;
    }


    public function beforeSave()
    {
        foreach ($this->attributes as $key => $value)
            if (!$value) {
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

        if ($currentUser->can('viewAllProfilesLibrary')) {
            $searchModel = new UserSearch();
            $dataProvider = $searchModel->search($this->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else return $this->actionView($currentUser->identity->id);
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

        if ($currentUser->role == 'reader') {
            return $this->render('view', [
                'model' => $this->findModel($currentUser->id),
            ]);
        } else {
            $searchModel    = new LentToSearch();
            $historyDataProvider   = $searchModel->search($this->request->queryParams, $id, false);
            $myBooksDataProvider   = $searchModel->search($this->request->queryParams, $id, true);

            return $this->render('viewLibrarian', [
                'model' => $this->findModel($id),
                'searchModel' => $searchModel,
                'historyDataProvider' => $historyDataProvider,
                'myBooksDataProvider' => $myBooksDataProvider,
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

        if (Yii::$app->user->can('manageUsers')) {
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

        return $this->actionIndex();
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

        if ($currentUser->role == 'reader') {
            $model = $this->findModel($currentUser->id);
        } else {
            $model = $this->findModel($id);
        }

        /// Get current role object for user($id)
        $auth = \Yii::$app->authManager;
        $oldRole = $auth->getAssignments($id);
        $oldRole = array_keys($oldRole)[0];  // Get role name as string.
        $oldRole = $auth->getRole($oldRole); // Get role object by string... Yeah, I know.
        $newRole = NULL;

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            // If roles change in user table, apply changes in the RBAC,
            $newRole = $auth->getRole($model->role);
            if ($newRole != $oldRole->name && $newRole != NULL) {
                $auth->revoke($oldRole, $id);
                $auth->assign($newRole, $id);
            }

            // Apply changes to password.
            if ($model->newPassword) {
                $model->setPassword($model->newPassword);
            }
            $model->save();

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
        if (Yii::$app->user->can('manageUsers')) {
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

        throw new NotFoundHttpException('Cannot find the page of the user you are looking for :/');
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
            Yii::$app->setHomeUrl(Url::to(['book/index']));
            return $this->goHome();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }


    public function actionSuspend($id)
    {
        if (Yii::$app->user->can('suspendOrNote')) {
            $model = $this->findModel($id);
            
            if ($model->load(Yii::$app->request->post())) {
                if ($model->suspended_status == 'yes'){
                    $model->suspended_date = date('Y-m-d');
                }else {
                    $model->suspended_date = null;
                }

                if(!$model->save()){
                    Yii::$app->session->setFlash('error','Could not save changes to model');
                }
                return $this->redirect(['view','id'=>$id]);
            }

            return $this->render('_formSuspend',[
                'id' => $id,
                'model' => $model,
            ]);
        }
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


    public function actionLendform()
    {
        $model = new \app\models\LentTo();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                // form inputs are valid, do something here
                return;
            }
        }

        return $this->render('_lendForm', [
            'model' => $model,
        ]);
    }


    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['book/index']);
        }

        $model = new SignupForm();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            return $this->redirect(Yii::$app->homeUrl);
        }

        return $this->render("signup", ['model' => $model]);
    }


    public function actionLendhistory()
    {
        if (Yii::$app->user->can('viewAllHistory')) {
            $searchModel = new LentToSearch();
            $dataProvider = $searchModel->search($this->request->queryParams);

            return $this->render('lendHistory', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

        return $this->redirect(['book/index']);
    }


    public function actionMybooks()
    {
        $notReturnedOnly = true;
        return $this->userHistory(Yii::$app->User->id, $notReturnedOnly);
    }


    public function actionMyhistory()
    {
        $allCategories = false;
        return $this->userHistory(Yii::$app->User->id, $allCategories);
    }


    public function userHistory($user_id, $notReturnedOnly)
    {
        if (Yii::$app->user->can('viewAllProfilesLibrary' == false)) {
            $user_id = Yii::$app->user->identity->id;
        }

        $searchModel    = new LentToSearch();
        $dataProvider   = $searchModel->search($this->request->queryParams, $user_id, $notReturnedOnly);

        return $this->render($notReturnedOnly?'myBooks':'myHistory', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Create a cart object in session['cart'] with the following structure:
     * Session['cart'] = [
     *      'user' => [
     *          'id'   => [user ID],
     *          'name' => [First and Last name]
     *      ],
     *      'librarian' => [librarian ID],
     *      'book' => [
     *          [book1 ISBN] => [
     *              'title' => [Book title],
     *              'amount'=> [take amount],
     *          ],
     *          [book2 ISBN] => [
     *              'title' => [Book title],
     *              'amount'=> [take amount],
     *          ]
     *          . . . 
     *      ]
     * ]
     * 
     * Due to a quirk of Yii2, you have to assign the cart in the session to a 
     * local variable, change that local variable and assign it back to the session.
     * like this:
     * 
     * $cart = $session['cart'];
     * [ change something in $cart . . . ]
     * $session['cart'] = $cart; <- save changes to session.
     * 
     */
    public function actionGive($id)
    {
        if (Yii::$app->user->can('manageBook') == false) {
            return $this->redirect(['index']);
        }

        $session = Yii::$app->session;

        if ($session->has('cart')) {
            $session->remove('cart');
        }
        $userModel = $this->findModel($id);

        $cart = [
            'user' => [
                'id' => $id,
                'name' => "{$userModel->first_name} {$userModel->last_name}",
            ],
            'librarian' => Yii::$app->user->identity->id,
            'book' => [],
        ];

        $reservedBooks = LentTo::findAll([
            'user_id' => $id,
            'status' => 'reserved',
        ]);

        foreach($reservedBooks as $model){
            $bookModel = Book::findOne(['isbn'=>$model->book_isbn]);
            $cart['book'][$model->book_isbn] = [
                'title' => $bookModel->title . ' - RESERVED',
                'amount' => 1,
            ];
        }

        $session->set('cart', $cart);

        return $this->redirect(['/book/index']);
    }


    public function actionProblemreaders()
    {
        if (Yii::$app->user->can('viewAllHistory') == false) {
            return $this->redirect(['index']);
        }

        $searchModel = new LentToSearch();
        $dataProviderDays = $searchModel->searchProblemDays($this->request->queryParams);
        $dataProviderLate = $searchModel->searchProblemLate($this->request->queryParams);
        return $this->render('problemReaders', [
            'searchModel' => $searchModel,
            'dataProviderDays' => $dataProviderDays,
            'dataProviderLate' => $dataProviderLate,
        ]);
    }


    /**
     * Render a page that shows a list of all reserved books.
     */
    public function actionReserved()
    {
        if (Yii::$app->user->can('viewAllHistory') == false) {
            return $this->redirect(['index']);
        }

        $searchModel = new LentToSearch();
        $dataProvider = $searchModel->searchReserved($this->request->queryParams);

        return $this->render('reserved', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

        return $this->redirect(['book/index']);
    }


    /**
     * Cancels a single reservation given user and book IDs
     * Effectively deletes a row from lent_to table.
     */
    public function actionCancelreserved($isbn, $userID)
    {
        if (Yii::$app->user->can('viewAllHistory') == false) {
            $userID = Yii::$app->user->identity->id;
        }

        $lentTo = LentTo::findOne([
            'user_id' => $userID,
            'book_isbn' => $isbn,
            'status' => 'reserved'
        ]);

        if($lentTo == null){
            Yii::$app->session->setFlash('warning','Could not find reserved book.');
            return $this->redirect(['reserved']);
        }

        $lentTo->delete();

        $book = Book::findOne(['isbn' => $isbn]);
        $book->available_count++;
        $book->save();

        return $this->redirect(Yii::$app->request->referrer);
    }


    /**
     * Cancel all reservations by all users.
     * Deletes all rows with status 'reserved' from 
     * lent_to table.
     */
    public function actionCancelreservedall()
    {
        if (Yii::$app->user->can('viewAllHistory') == false) {
            return $this->redirect(['index']);
        }

        $lentTo = LentTo::findAll(['status' => 'reserved']);
        foreach ($lentTo as $model) {
            $book = Book::findOne($model->book_isbn);
            $book->available_count++;
            $book->save();
            $model->delete();
        }

        return $this->redirect(['reserved']);
    }
}
