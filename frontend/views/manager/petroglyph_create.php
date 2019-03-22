<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \common\models\Petroglyph */

use yii\helpers\Html;

$this->title = 'Добавление';
$this->params['breadcrumbs'] = [
    ['label' => 'Управление контентом', 'url' => ['/manager/index']],
    ['label' => 'Петроглиф', 'url' => ['/manager/petroglyph']],
    $this->title,
];
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_petroglyph_form', [
    'model' => $model,
    'cultures' => $cultures,
    'epochs' => $epochs,
    'methods' => $methods,
]) ?>
