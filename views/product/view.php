<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <hr/>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
            'price',
            'country',
            'year',
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
    ]) ?>

</div>
