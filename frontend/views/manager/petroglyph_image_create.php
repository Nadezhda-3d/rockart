<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \common\models\PetroglyphImage */

use yii\helpers\Html;

$this->title = 'Добавление изображения';
$this->params['breadcrumbs'] = [
    ['label' => 'Управление контентом', 'url' => ['/manager/index']],
    ['label' => 'Петроглиф', 'url' => ['/manager/petroglyph']],
    ['label' => $petroglyph->name, 'url' => ['/manager/petroglyph-view', 'id' => $petroglyph->id]],
    $this->title,
];
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_petroglyph_image_form', [
    'model' => $model,
    'petroglyph' => $petroglyph,
]) ?>
