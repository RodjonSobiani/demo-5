<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>Пожалуйста, заполните необходимые поля, чтобы зарегистрироваться в системе: </p>
    <hr/>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
