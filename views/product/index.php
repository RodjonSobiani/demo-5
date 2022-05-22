<?php

use app\models\Category;
use app\models\Product;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;

$items = Category::find()
    ->select(['name'])
    ->indexBy('id')
    ->column();
?>

<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <hr/>
    <p>
        <?= Html::a('Добавить товар', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Product $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
