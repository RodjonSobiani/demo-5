<?php

namespace app\controllers;

use app\models\Cart;
use app\models\Order;
use app\models\Product;
use app\models\ProductOrder;
use app\models\ProductSearch;
use app\models\Status;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout', 'cart', 'to-cart', 'add-cart', 'remove-cart', 'by-order'],
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

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCart()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Cart::find()->where(['user_id' => Yii::$app->user->getId()]),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('cart', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionToCart($product_id)
    {
        $product = Product::find()
            ->where(['id' => $product_id])
            ->andWhere(['>', 'count', 0])
            ->one();

        if (!$product) {
            return 'Такого товара нет в наличии';
        }

        $itemInCart = Cart::find()
            ->where(['product_id' => $product_id])
            ->andWhere(['user_id' => Yii::$app->user->getId()])
            ->one();

        if (!$itemInCart) {
            $itemInCart = new Cart([
                'product_id' => $product_id,
                'user_id' => Yii::$app->user->getId(),
                'count' => 1
            ]);
            $itemInCart->save();
            return 'Товар добавлен в корзину. Количество элементов в корзине: ' . $itemInCart->count;
        }

        if ($itemInCart->count + 1 > $product->count) {
            return 'Такого товара больше нет в наличии';
        }

        $itemInCart->count++;
        $itemInCart->save();
        return 'Товар добавлен в корзину. Количество элементов в корзине: ' . $itemInCart->count;
    }

    public function actionRemoveCart($product_id)
    {
        $itemInCart = Cart::find()
            ->where(['product_id' => $product_id])
            ->andWhere(['user_id' => Yii::$app->user->getId()])
            ->one();

        if (!$itemInCart) {
            return;
        }

        if ($itemInCart->count - 1 === 0) {
            $itemInCart->delete();
            return;
        }

        $itemInCart->count--;
        $itemInCart->save();
        return;
    }

    public function actionByOrder($password)
    {
        if (!Yii::$app->user->identity->validatePassword($password)) {
            return 'Неправильный пароль';
        };

        $order = new Order([
            'user_id' => Yii::$app->user->getId(),
            'status_id' => Status::find()->where(['code' => 'new'])->one()->id,
        ]);
        $order->save();

        $itemInCart = Yii::$app->user->identity->carts;

        foreach ($itemInCart as $item) {
            $itemInOrder = new ProductOrder([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'count' => $item->count,
                'price' => $item->count * $item->product->price,
            ]);
            $itemInOrder->save();
            $item->delete();
        }
        return 'Заказ сформирован';
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
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
        Yii::$app->user->logout();

        return $this->redirect('/site/about');
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        $products = Product::find()->limit(5)->orderBy('id DESC')->all();
        return $this->render('about', [
            'products' => $products,
        ]);
    }
}
