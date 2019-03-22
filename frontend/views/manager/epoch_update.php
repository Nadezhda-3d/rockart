<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \common\models\Epoch */

use yii\helpers\Html;

$this->title = 'Редактирование';
$this->params['breadcrumbs'] = [
    ['label' => 'Управление контентом', 'url' => ['/manager/index']],
    ['label' => 'Эпоха', 'url' => ['/manager/epoch']],
    $this->title,
];
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_epoch_form', [
    'model' => $model,
]) ?>
