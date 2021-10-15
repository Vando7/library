<?php

namespace app\controllers;

use Yii;

use app\models\Cart;
use app\models\Book;
use app\models\Genre;
use app\models\LentTo;
use app\models\LentToSearch;
use app\models\BookGenre;
use app\models\BookSearch;

use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\web\CreateUrl;
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
            return $this->redirect(['user/login'])->send();
        }
        return parent::beforeAction($action);
    }


    /**
     * Lists all Book models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel    = new BookSearch();
        $dataProvider   = $searchModel->search($this->request->queryParams,);
        $dataProvider->setPagination (['pageSize' => 10,]);
        $cartModel = Yii::$app->session->has('cart') ? new Cart : 'NULL';

        return $this->render('index', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
            'genreList'     => $this->getGenreNames(),
            'cartModel'     => $cartModel,
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

        foreach ($genresQuery as $genreObject) {
            array_push($genres, $genreObject->name);
        }

        $searchModel    = new LentToSearch();
        $dataProvider   = $searchModel->searchPerBook($this->request->queryParams, $isbn);

        $lentTo = LentTo::findOne([
            'book_isbn' => $isbn,
            'user_id' => Yii::$app->user->identity->id,
            'status' => 'reserved',
        ]);

        $reserveButton = $lentTo == null ? 'true' : '';

        return $this->render('view', [
            'model' => $model,
            'genres' => $genres,
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
            'reserveButton' => $reserveButton,
        ]);
    }


    /**
     * Uploads pictures specified in the create form.
     * - maybe  can be moved to book model.
     */
    public function uploadPictures($model)
    {
        $model->bookCover   = UploadedFile::getInstance($model, 'bookCover');
        $model->bonusImages = UploadedFile::getInstances($model, 'bonusImages');

        $counter = 0;
        $picturesJson = [];
        $picturesJson = json_decode($model->pictures, true);

        if ($model->bookCover) {
            $picturesJson['cover'] = 'upload/' . $model->isbn . '_cover.' . $model->bookCover->extension;
        }

        $counter = 1;
        if ($model->bonusImages) {
            foreach ($model->bonusImages as $files) {
                $picturesJson['extra' . $counter] = 'upload/' . $model->isbn . '_extra' . $counter . '.'  . $files->extension;

                ++$counter;
            }

            while (file_exists('upload/' . $model->isbn . '_extra' . $counter)) {
                unlink('upload/' . $model->isbn . '_extra' . $counter);

                ++$counter;
            }
        }

        $model->pictures = json_encode($picturesJson);
        $model->save();
        error_log((VarDumper::dumpAsString($model)),3,'ivan_log.txt');
        $model->upload();
        return true;
    }


    /**
     * Creates a new Book model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->user->can('manageBook')) {
            $model = new Book();

            if ($this->request->isPost) {
                if ($model->load($this->request->post()) && $model->save()) {

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
        } else return $this->actionIndex();
    }


    /**
     * Returns a list of all book genres in the following format:
     * [ genre_id => genre_name ]
     */
    public function getGenreNames()
    {
        $genreDB    = new Genre;
        $genreListRaw  = $genreDB->find()->all();
        $genreList = [];

        foreach ($genreListRaw as $genreObj) {
            $genreList += [$genreObj->id => $genreObj->name];
        }

        return $genreList;
    }


    /**
     * Removes previous genres the book had and writes the
     * ones currently written in $model->genreList
     */
    public function saveBookGenres($model)
    {
        $bookGenreList = $model->genres;

        foreach ($bookGenreList as $bookGenreObj) {
            $model->unlink('genres', $bookGenreObj, true);
        }

        foreach ($model->genreList as $genreObj) {
            $newGenre = new BookGenre;
            $newGenre->genre_id = $genreObj;
            $newGenre->link('bookIsbn', $model);
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

        if (Yii::$app->user->can('manageBook')) {
            if ($this->request->isPost && $model->load($this->request->post())) {

                $this->uploadPictures($model);
                $this->saveBookGenres($model);

                return $this->redirect(['view', 'isbn' => $model->isbn]);
            }

            $genresQuery = $model->genres;

            foreach ($genresQuery as $genreObj) {
                array_push($model->genreList, $genreObj->id);
            }

            return $this->render('update', [
                'model'     => $model,
                'genreList' => $this->getGenreNames(),
            ]);
        } else
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
        if (Yii::$app->user->can('manageBook')) {
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


    protected function findGenre($id)
    {
        if (($model = Genre::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested genre does not exist.');
    }


    public function actionCreategenre()
    {
        if (Yii::$app->user->can('manageBook')) {
            $model = new Genre;

            $model->load(Yii::$app->request->post());
            $model->save();
        }

        $newGenre   = new Genre;
        $genreListRaw  = $newGenre->find()->all();

        return $this->renderAjax('viewGenre', [
            'genreList' => $genreListRaw,
            'newGenre'  => $newGenre,
        ]);
    }


    public function actionDeletegenre($id)
    {
        if (Yii::$app->user->can('manageBook')) {
            $this->findGenre($id)->delete();

            $newGenre   = new Genre;
            $genreListRaw  = $newGenre->find()->all();

            return $this->renderAjax('viewGenre', [
                'genreList' => $genreListRaw,
                'newGenre'  => $newGenre,
            ]);
        }
    }


    public function actionViewgenre($isModal = true)
    {
        $newGenre   = new Genre;
        $genreListRaw  = $newGenre->find()->all();

        if ($isModal) {
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


    public function actionAddtocart($isbn, $availableBooks)
    {

        if (Yii::$app->user->can('manageBook') == false) {
            return $this->redirect(['index']);
        }

        $session = Yii::$app->session;
        $model = new Cart;

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->amount > $availableBooks) {
                $session->setFlase('warning', 'The amount you requested is greater than the one available.');
                return $this->redirect(['index']);
            }

            $bookModel = $this->findModel($isbn);
            $cart = $session['cart'];

            if (array_key_exists($isbn, $cart['book'])) {
                if ($model->amount <= 0) {
                    unset($cart['book'][$isbn]);
                } else {
                    $cart['book'][$isbn] = [
                        'title' => $bookModel->title,
                        'amount' => $model->amount,
                    ];
                }
            } else {
                $cart['book'] += [
                    $isbn => [
                        'title' => $bookModel->title,
                        'amount' => $model->amount,
                    ]
                ];
            }

            $session['cart'] = $cart;
        }

        return $this->redirect(['index']);
    }


    public function actionRemovefromcart($isbn)
    {
        $session = Yii::$app->session;

        if ($session->has('cart') == false) {
            return $this->redirect(['index']);
        }

        $cart = $session['cart'];
        unset($cart['book'][$isbn]);
        $session['cart'] = $cart;

        return $this->redirect(['index']);
    }


    public function actionClearcart()
    {
        $session = Yii::$app->session;

        if ($session->has('cart')) {
            $session->remove('cart');
        }

        return $this->redirect(['index']);
    }


    public function actionClearcartitems()
    {
        $session = Yii::$app->session;

        if ($session->has('cart')) {
            $cart = $session['cart'];
            $cart['book'] = [];
            $session['cart'] = $cart;
        }
        return $this->redirect(['index']);
    }


    public function actionCheckout()
    {
        $session = Yii::$app->session;
        if ($session->has('cart') == false) {
            return $this->redirect(['index']);
        }

        $cartModel = new Cart;
        if ($this->request->isPost && $cartModel->load($this->request->post())) {
            $cart = $session['cart'];

            if ($cart['book'] === []) {
                return $this->redirect(['index']);
            }

            foreach ($cart['book'] as $isbn => $bookInfo) {
                if ($cartModel->deadline == null) {
                    $cartModel->deadline = date("Y-m-d", strtotime('+30 days'));
                }

                $reservedBook = LentTo::findOne([
                    'book_isbn' => $isbn,
                    'user_id'   => $cart['user']['id'],
                    'status'    => 'reserved'
                ]);

                $reservedAmount = 0;
                if ($reservedBook != null) {
                    $reservedAmount = 1;
                    $book = Book::findOne(['isbn' => $isbn]);
                    $book->available_count++;
                    $book->save();
                    $reservedBook->delete();
                }

                $model = new LentTo;
                $model->book_isbn   = $isbn;
                $model->user_id     = $cart['user']['id'];
                $model->employee_id = $cart['librarian'];
                $model->amount      = $bookInfo['amount'];
                $model->date_lent   = date("Y-m-d H:i:s"); // today
                $model->deadline    = $cartModel->deadline;
                $model->status      = 'taken';

                $book = $this->findModel($model);
                $book->available_count -= $bookInfo['amount'];

                $db = Yii::$app->db;
                $transaction = $db->beginTransaction();
                try {
                    $book->save();
                    $model->save();
                    unset($session['cart']);

                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
                $session->setFlash('success', 'Books given successfully!');
            }
        }

        return $this->redirect(['index']);
    }


    public function actionReturn($id)
    {
        if (Yii::$app->user->can('manageBook') == false) {
            return $this->redirect(['index']);
        }

        $searchModel    = new LentToSearch;
        $dataProvider   = $searchModel->searchTitle($this->request->queryParams, $id);

        return $this->render('_returnForm', [
            'user_id' => $id,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReturnbook($id, $isbn, $dateLent)
    {
        if (Yii::$app->user->can('manageBook') == false) {
            return $this->redirect(['index']);
        }

        $key = [
            'user_id' => $id,
            'book_isbn' => $isbn,
            'date_lent' => $dateLent,
        ];

        $lentTo = LentTo::findOne($key);
        $lentTo->status = 'returned';
        $lentTo->date_returned = date("Y-m-d H:i:s");

        $book = $this->findModel($isbn);
        $book->available_count += $lentTo->amount;

        $book->save();
        $lentTo->save();

        return $this->redirect(['return', 'id' => $id]);
    }


    public function actionReservebook($isbn)
    {
        $currentUser = Yii::$app->user->identity;

        $lentTo = LentTo::findOne([
            'book_isbn' => $isbn,
            'user_id' => $currentUser->id,
            'status'  => 'reserved',
        ]);

        if ($lentTo != null) {
            $this->redirect(['view', 'isbn' => $isbn]);
        }

        $book = $this->findModel($isbn);
        if ($book->available_count <= 0) {
            $this->redirect(['view', 'isbn' => $isbn]);
        }

        $model = new LentTo;

        $model = new LentTo;
        $model->book_isbn   = $isbn;
        $model->user_id     = $currentUser->id;
        $model->employee_id = $currentUser->id;
        $model->amount      = 1;
        $model->date_lent   = date("Y-m-d H:i:s"); // today
        $model->deadline    = date("Y-m-d H:i:s"); // today
        $model->status      = 'reserved';


        $book->available_count--;

        $book->save();
        $model->save();

        $this->redirect(['view', 'isbn' => $isbn]);
    }
}
