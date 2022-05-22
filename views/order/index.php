<?php

use app\models\Order;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мои заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <hr/>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Количество',
                'value' => function ($data) {
                    $productCount = 0;
                    foreach ($data->productOrders as $key => $item) {
                        $productCount += $item->count;
                    }
                    return $productCount;
                }
            ],

            [
                'label' => 'Список товаров',
                'format' => 'raw',
                'value' => function ($data) {
                    $res = [];
                    foreach ($data->productOrders as $key => $item) {
                        $res[] = $key + 1 . ') ' . $item->product->name . ': ' . $item->count . ' шт.';
                    }
                    return join('<br/>', $res);
                }
            ],
            [
                'attribute' => 'status_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getOrderStatus();
                }
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{delete}',
                'visibleButtons' => [
                    'delete' => function ($model, $key, $index) {
                        return $model->status->code === 'new';
                    }
                ],
                'urlCreator' => function ($action, Order $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
