<?php

/* @var $this yii\web\View */
/* @var $model Petroglyph */

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Petroglyph;

$this->title = 'Петроглиф';
$this->params['breadcrumbs'] = [
    ['label' => 'Управление контентом', 'url' => ['/manager/index']],
    $this->title,
];
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="text-right">
    <?= Html::a('Добавить', ['manager/petroglyph-create'], ['class' => 'btn btn-primary'])?>
</div>

<br>

<div class="clearfix"></div>

<?= GridView::widget([
    'dataProvider' => $provider,
    'columns' => [
        'id',
        'name',
        'lat',
        'lng',
        [
            'attribute' => 'culture_id',
            'format' => 'text',
            'value' => function ($model) {
                return isset($model->culture) ? $model->culture->name : null;
            }
        ],
        [
            'attribute' => 'epoch_id',
            'format' => 'text',
            'value' => function ($model) {
                return isset($model->epoch) ? $model->epoch->name : null;
            }
        ],
        [
            'attribute' => 'method_id',
            'format' => 'text',
            'value' => function ($model) {
                return isset($model->method) ? $model->method->name : null;
            }
        ],
        [
            'attribute' => 'public',
            'format' => 'text',
            'value' => function ($model) {
                return $model->public ? 'Опубликован' : 'Скрыт';
            }
        ],
        [
            'attribute' => 'image',
            'format' => 'html',
            'value' => function ($model) {
                return empty($model->image) ? null : Html::img(Petroglyph::SRC_IMAGE . '/' .  $model->thumbnailImage, ['width' => 100]);
            }
        ],
        [
            'class' => 'backend\grid\ActionColumn',
            'options' => ['style' => 'width: 100px;'],
            'buttons' => [
                'view' => function ($url, $model) {
                    return \yii\helpers\Html::a(
                        '<span class="fas fa-eye"></span>',
                        ['manager/petroglyph-view', 'id' => $model->id]);
                },
                'update' => function ($url, $model) {
                    return \yii\helpers\Html::a(
                        '<span class="fas fa-edit"></span>',
                        ['manager/petroglyph-update', 'id' => $model->id]);
                },
                'delete' => function ($url, $model) {
                    return \yii\helpers\Html::a(
                        '<span class="fas fa-trash"></span>',
                        ['manager/petroglyph-delete', 'id' => $model->id],
                        [
                            'data-pjax' => "0",
                            'data-confirm' => "Вы уверены, что хотите удалить этот элемент?",
                            'data-method' => "post"
                        ]);
                }
            ],
        ],
    ],
]) ?>
