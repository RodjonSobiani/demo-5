<?php

use app\models\Order;
use app\models\Status;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Админ-панель';

$items = Status::find()
    ->select(['name'])
    ->indexBy('id')
    ->column();
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <hr/>

    <div class="form-group">
        <a class="btn btn-success button-hover" href="/product">Управление товарами</a>
        <a class="btn btn-success button-hover" href="/category">Управление категориями</a>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'status_id',
                'filter' => $items,
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getOrderStatus();
                }
            ],
            [
                'label' => 'Управление<br/>статусом',
                'encodeLabel' => false,
                'format' => 'raw',
                'value' => function ($data) {
                    switch ($data->status_id) {
                        case 1:
                            return Html::a('Одобрить', '/admin/approve?id=' . $data->id) . ' <br/> ' .
                                Html::a('Отклонить', '/admin/reject?id=' . $data->id);
                        case 2:
                            return Html::a('Отклонить', '/admin/reject?id=' . $data->id) . ' <br/> ' .
                                Html::a('Обновить', '/admin/renew?id=' . $data->id);
                        case 3:
                            return Html::a('Одобрить', '/admin/approve?id=' . $data->id) . ' <br/> ' .
                                Html::a('Обновить', '/admin/renew?id=' . $data->id);
                    }
                }
            ],
            'date',
            [
                'attribute' => 'user_id',
                'value' => function ($data) {
                    return $data->user->surname . ' ' . $data->user->name . ' ' . $data->user->patronymic;
                }
            ],

            [
                'label' => 'Количество товаров',
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
                'attribute' => 'rejected_reason',
                'label' => 'Причина отклонения или<br/>другой комментарий',
                'encodeLabel' => false,
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{update} {delete}',
                'urlCreator' => function ($action, Order $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
