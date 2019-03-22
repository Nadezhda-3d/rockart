<?php

/* @var $this yii\web\View */


$this->registerCssFile('css/home.css?201903131429', ['depends' => ['yii\bootstrap\BootstrapPluginAsset']]);

$this->title = Yii::$app->name;
?>

<?= $this->render('_banner') ?>

<div class="row">
    <div class="col-xs-12">
        <p>
            <?= Yii::t('home', 'text') ?>
        </p>
    </div>
</div>
