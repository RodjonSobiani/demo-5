<?php

use app\models\Order;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Корзина';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile(
    '@web/js/main.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <hr/>

    <?php Pjax::begin(['id' => 'cart']) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'product.name',
            'count',
            [
                'label' => 'Добавить в корзину',
                'format' => 'raw',
                'value' => function ($data) {
                    return "<button class='btn btn-success' onclick='addCart($data->product_id)'>+</button>";
                },
            ],

            [
                'label' => 'Удалить из корзины',
                'format' => 'raw',
                'value' => function ($data) {
                    return "<button class='btn btn-danger' onclick='removeCart($data->product_id)'>-</button>";
                },
            ],
        ],
    ]); ?>
    <?php Pjax::end() ?>
    <div class="form-group">
        <p>Пожалуйста, введите пароль для подтверждения Вашего заказа: </p>
        <?= Html::input('password', 'password', '', ['class' => 'password form-control form-size']) ?>
    </div>
    <button class="btn btn-success button-hover" onclick="byOrder()">Отправить заявку</button>
</div>
