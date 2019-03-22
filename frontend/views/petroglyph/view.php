<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $petroglyph Petroglyph */

use common\models\Petroglyph;
use common\models\PetroglyphImage;
use yii\helpers\Html;

$this->title = $petroglyph->name;
$this->params['breadcrumbs'] = [
    ['label' => Yii::t('app', 'Petroglyphs'), 'url' => ['petroglyph/index']],
    $this->title,
];

$this->registerCssFile('css/petroglyph.css', ['depends' => ['yii\bootstrap\BootstrapPluginAsset']]);

$mdCol = Yii::$app->user->can('manager') ? 3 : 4;
?>

<?= newerton\fancybox\FancyBox::widget([
    'target' => 'a[rel=petroglyphImages]',
    'helpers' => true,
    'mouse' => true,
    'config' => [
        'maxWidth' => '90%',
        'maxHeight' => '90%',
        'playSpeed' => 7000,
        'padding' => 0,
        'fitToView' => false,
        'width' => '70%',
        'height' => '70%',
        'autoSize' => false,
        'closeClick' => false,
        'openEffect' => 'elastic',
        'closeEffect' => 'elastic',
        'prevEffect' => 'elastic',
        'nextEffect' => 'elastic',
        'closeBtn' => false,
        'openOpacity' => true,
        'helpers' => [
            'title' => ['type' => 'float'],
            'buttons' => [
            ],
            'thumbs' => ['width' => 68, 'height' => 50],
            'overlay' => [
                'css' => [
                    'background' => 'rgba(0, 0, 0, 0.8)'
                ]
            ]
        ],
    ]
]) ?>

<?php if (empty($petroglyph->image)): ?>
    <?php if (Yii::$app->user->can('manager')): ?>
        <?= Html::a(Yii::t('app', 'Edit'), ['manager/petroglyph-update', 'id' => $petroglyph->id], ['class' => 'btn btn-primary pull-right']) ?>
    <?php endif; ?>
    <h1><?= Html::encode($petroglyph->name) ?></h1>
    <?= $petroglyph->description ?>
<?php else: ?>
    <div class="pull-left poster">
        <?= Html::a(Html::img(Petroglyph::SRC_IMAGE . '/' . $petroglyph->thumbnailImage, [
            'class' => 'img-responsive'
        ]), Petroglyph::SRC_IMAGE . '/' . $petroglyph->image, [
            'rel' => 'petroglyphImages'
        ]); ?>
    </div>
    <?php if (Yii::$app->user->can('manager')): ?>
        <?= Html::a(Yii::t('app', 'Edit'), ['manager/petroglyph-update', 'id' => $petroglyph->id], ['class' => 'btn btn-primary pull-right']) ?>
    <?php endif; ?>

    <h1><?= Html::encode($petroglyph->name) ?></h1>

    <?= $petroglyph->description ?>

    <div class="clearfix"></div>

    <div class="row">
        <?php if (Yii::$app->user->can('manager')): ?>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <?= $this->render('_panel', [
                    'title' => Yii::t('app', 'Coordinates'),
                    'data' => [$petroglyph->lat, $petroglyph->lng],
                ]) ?>
            </div>
        <?php endif; ?>
        <div class="col-xs-12 col-sm-6 col-md-<?= $mdCol ?>">
            <?= $this->render('_panel', [
                'title' => Yii::t('app', 'Culture'),
                'data' => [(isset($petroglyph->culture) ? $petroglyph->culture->name : null)],
            ]) ?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-<?= $mdCol ?>">
            <?= $this->render('_panel', [
                'title' => Yii::t('app', 'Epoch'),
                'data' => [(isset($petroglyph->epoch) ? $petroglyph->epoch->name : null)],
            ]) ?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-<?= $mdCol ?>">
            <?= $this->render('_panel', [
                'title' => Yii::t('app', 'Method'),
                'data' => [(isset($petroglyph->method) ? $petroglyph->method->name : null)],
            ]) ?>
        </div>
    </div>

<?php endif; ?>

    <div class="clearfix"></div>

    <h3><?= Yii::t('app', 'Additional Images') ?></h3>

<?php if (!empty($petroglyph->images)): ?>
    <div class="row images">
        <?php foreach ($petroglyph->images as $item): ?>
            <div class="col-xs-6 col-sm-4 col-md-3">
                <div class="image">
                    <?= Html::a(Html::img(PetroglyphImage::SRC_IMAGE . '/' . $item->thumbnailImage, [
                        'class' => 'img-responsive img-thumbnail'
                    ]), PetroglyphImage::SRC_IMAGE . '/' . $item->file, [
                        'rel' => 'petroglyphImages'
                    ]); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>