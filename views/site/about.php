<?php

/** @var yii\web\View $this */

/** @var array<Product> $products */

use app\models\Product;
use yii\helpers\Html;

$this->title = 'О нас';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <hr/>
    <div class="text-center">
        <p>Мы активная и развивающая компания "Название компании".</p>
        <p>Наш девиз: "ТРА-ТА-ТА"</p>
        <img src="/web/uploads/2022-05-17-150325.png" alt="logo" width="25px">
    </div>
    <hr/>

    <?php if (count($products) > 0): ?>
        <div id="carouselExampleCaptions" class="carousel slide carousel-fade" data-ride="carousel">
            <div class="carousel-inner">
                <?php if (count($products) > 1): ?>
                    <?php foreach ($products as $key => $product): ?>
                        <div class="carousel-item <?php if ($key === 0) echo 'active' ?>">
                            <a href="/product/view?id=<?= $product->id ?>"><img src="/web/uploads/<?= $product->file ?>"
                                                                                class="d-block slider-image"
                                                                                alt="<?= $product->name ?>"></a>
                            <br/><br/><br/><br/><br/>
                            <div class="carousel-caption d-none d-md-block text-dark">
                                <h5><?= $product->category->name ?></h5>
                                <p><?= $product->name ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-target="#carouselExampleCaptions"
                    data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-target="#carouselExampleCaptions"
                    data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </button>
        </div>
    <?php endif; ?>
</div>
