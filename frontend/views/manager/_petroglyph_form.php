<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\models\Petroglyph */
/* @var $form ActiveForm */
?>
<div class="manager-_petroglyph_form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <?= $form->field($model, 'name') ?>
            <?= $form->field($model, 'name_en') ?>
            <?= $form->field($model, 'description')->widget(CKEditor::className(),
                [
                    'editorOptions' => [
                        'preset' => 'standard',
                        'inline' => false,
                    ],
                    'options' => [
                        'allowedContent' => true,
                    ],

                ]) ?>
            <?= $form->field($model, 'description_en')->widget(CKEditor::className(),
                [
                    'editorOptions' => [
                        'preset' => 'standard',
                        'inline' => false,
                    ],
                    'options' => [
                        'allowedContent' => true,
                    ],

                ]) ?>
            <?= $form->field($model, 'lat') ?>
            <?= $form->field($model, 'lng') ?>
            <?= $form->field($model, 'method_id')->dropDownList($methods, ['prompt'=>Yii::t('manager', 'Select...')]) ?>
            <?= $form->field($model, 'culture_id')->dropDownList($cultures, ['prompt'=>Yii::t('manager', 'Select...')]) ?>
            <?= $form->field($model, 'epoch_id')->dropDownList($epochs, ['prompt'=>Yii::t('manager', 'Select...')]) ?>
            <?= $form->field($model, 'public')->checkbox() ?>
            <?= $form->field($model, 'fileImage')->fileInput() ?>

        </div>
        <div class="col-xs-12 col-md-6 text-right">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('manager', 'Save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div><!-- manager-_petroglyph_form -->
