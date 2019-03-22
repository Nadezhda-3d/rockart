<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Управление контентом';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<ul class="list-group">
    <li class="list-group-item">
        <?= Html::a('Культура', ['manager/culture']) ?>
    </li>
    <li class="list-group-item">
        <?= Html::a('Эпоха', ['manager/epoch']) ?>
    </li>
    <li class="list-group-item">
        <?= Html::a('Метод', ['manager/method']) ?>
    </li>
    <li class="list-group-item">
        <?= Html::a('Петроглиф', ['manager/petroglyph']) ?>
    </li>
</ul>
