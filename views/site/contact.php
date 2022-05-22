<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */

/** @var app\models\ContactForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\captcha\Captcha;

$this->title = 'Где нас найти?';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>
    <hr/>

    <h3 class="text-center">Наши контакты</h3>
    <p>Телефон: +7 903 941 4566</p>
    <p>Email: admin@mail.com</p>
    <p>Адрес: ул. Роылвао, д. 123</p>
    <p>Обращайтесь в любое время!</p>
    <hr/>

    <div class="text-center">
        <img src="/web/uploads/2022-05-21%20160543.png" alt="map">
    </div>
</div>
