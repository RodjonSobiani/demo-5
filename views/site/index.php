<?php

/** @var yii\web\View $this */
/* @var $searchModel app\models\ProductSearch */

/* @var $dataProvider yii\data\ActiveDataProvider */

use app\models\Category;
use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Каталог';
$this->params['breadcrumbs'][] = 'Каталог';
$items = Category::find()
    ->select(['name'])
    ->indexBy('id')
    ->column();

$this->registerJsFile(
    '@web/js/main.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
?>

<div class="site-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(['id' => 'cart']) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'file',
                'format' => 'raw',
                'value' => function ($data) {
                    return "<a href='/product/view?id=$data->id'><img src='/web/uploads/$data->file' alt='$data->name' width='150px'/></a>";
                }
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($data) {
                    return "<a href='/product/view?id=$data->id'>$data->name</a>";
                }
            ],
            'model',
            'year',
            'country',
            'price',

            [
                'attribute' => 'category_id',
                'filter' => $items,
                'value' => 'category.name',
            ],
            'count',

            [
                'label' => 'В корзину',
                'format' => 'raw',
                'value' => function ($data) {
                    return "<button class='btn btn-success' onclick='toCart($data->id)'>+</button>";
                },
                'visible' => Yii::$app->user->identity
            ],
        ],
    ]); ?>
    <?php Pjax::end() ?>
</div>
