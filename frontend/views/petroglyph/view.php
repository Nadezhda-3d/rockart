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

$script = <<< JS

    var arr = $json_petroglyphs,
        map_center = '{"lat": ' + parseFloat(arr[0].lat) + ', "lng": ' + parseFloat(arr[0].lng) + '}',
        date = new Date();

    date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
    var expires = ";expires=" + date.toUTCString();

    document.cookie = "map_center=" + map_center + expires + ";path=/";
    
JS;

$this->registerJs($script, yii\web\View::POS_BEGIN);

$script = <<< JS
        
     $('[data-toggle="tooltip"]').tooltip();

JS;

$this->registerJs($script, yii\web\View::POS_READY);

if (Yii::$app->user->can('manager')) {
    $this->registerJsFile('/js/map/jquery.cookie.js', ['depends' => ['yii\bootstrap\BootstrapPluginAsset']]);

    if ($mapProvider == 'yandex') {
        $this->registerJsFile('https://api-maps.yandex.ru/2.1/?lang=' . (Yii::$app->language == 'ru' ? 'ru_RU' : 'en_US') . '&mode=debug', ['depends' => ['yii\bootstrap\BootstrapPluginAsset']]);
        $this->registerJsFile('/js/map/tiler-converter.js', ['depends' => ['yii\bootstrap\BootstrapPluginAsset']]);
        $this->registerJsFile('/js/map/map_yandex.js', ['depends' => ['yii\bootstrap\BootstrapPluginAsset']]);
    } else {
        $this->registerJsFile('/js/map/markerclusterer/src/markerclusterer.js', ['depends' => ['yii\bootstrap\BootstrapPluginAsset']]);
        $this->registerJsFile('/js/map/map.js', ['depends' => ['yii\bootstrap\BootstrapPluginAsset']]);
        $this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyCeYhPhJAnwj95GXDg5BRT7Q2dTj303dQU&callback=initMap&language=' . Yii::$app->language, ['depends' => ['yii\bootstrap\BootstrapPluginAsset']]);
    }
}
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

    <h1>
        <?= Html::encode($petroglyph->name) ?>
    </h1>

    <?php if (!empty($petroglyph->index)): ?>
        <?= Yii::t('app', 'Index') . ': ' . $petroglyph->index ?>
    <?php endif; ?>

    <?= $petroglyph->description ?>

    <?php if (!empty($petroglyph->technical_description)): ?>
        <h3><?= Yii::t('app', 'Technical description') ?></h3>
        <?= $petroglyph->technical_description ?>
    <?php endif; ?>

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

<?php if (!empty($petroglyph->images)): ?>

    <div class="clearfix"></div>

    <h3><?= Yii::t('app', 'Additional Images') ?></h3>
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

<?php if (!empty($petroglyph->threeD)): ?>

    <div class="clearfix"></div>

    <h3><?= Yii::t('app', '3D Models') ?></h3>
    <div class="row images">
        <?php foreach ($petroglyph->threeD as $item): ?>
            <div class="col-xs-6 col-sm-4 col-md-3">
                <?= Html::a($item->name, $item->url, [
                    'class' => 'three-d fancybox',
                    'rel' => 'petroglyphImages',
                    'data-fancybox-type' => 'iframe',
                ]) ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (Yii::$app->user->can('manager')): ?>

    <div class="clearfix"></div>
    <div class="pull-right hidden-xs">
        <?= Html::a('Google Maps', '?mapProvider=google', ['class' => 'btn ' . ($mapProvider != 'yandex' ? 'btn-primary' : 'btn-default')]) ?>
        <?= Html::a('Yandex Maps', '?mapProvider=yandex', ['class' => 'btn ' . ($mapProvider == 'yandex' ? 'btn-primary' : 'btn-default')]) ?>
    </div>
    <h3><?= Yii::t('app', 'Map') ?></h3>
    <div class="visible-xs">
        <div class="form-group">
            <?= Html::a('Google Maps', '?mapProvider=google', ['class' => 'btn ' . ($mapProvider != 'yandex' ? 'btn-primary' : 'btn-default')]) ?>
            <?= Html::a('Yandex Maps', '?mapProvider=yandex', ['class' => 'btn ' . ($mapProvider == 'yandex' ? 'btn-primary' : 'btn-default')]) ?>
        </div>
    </div>


    <div id="map_canvas" style="width:100%; height:600px; float:left; margin-right: 20px;"></div>

<?php endif; ?>

<?php if (!empty($petroglyph->publication)): ?>

    <div class="clearfix"></div>
    <h3><?= Yii::t('app', 'Publications') ?></h3>
    <?= $petroglyph->publication ?>

<?php endif; ?>