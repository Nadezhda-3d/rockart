<?php

namespace frontend\controllers;

use common\models\Culture;
use common\models\Epoch;
use common\models\Method;
use common\models\Petroglyph;
use common\models\PetroglyphImage;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\UploadedFile;

/**
 * Manager controller
 */
class ManagerController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['manager'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'culture-delete' => ['post'],
                    'epoch-delete' => ['post'],
                    'method-delete' => ['post'],
                    'petroglyph-delete' => ['post'],
                    'petroglyph-image-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return string
     */
    public function actionCulture()
    {
        $query = Culture::find();
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);

        return $this->render('culture_list', ['provider' => $provider]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCultureCreate()
    {
        $model = new Culture();

        if ($model->load(\Yii::$app->request->post())) {

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', "Данные внесены");

                return $this->redirect(['manager/culture-view', 'id' => $model->id]);
            }

            \Yii::$app->session->setFlash('error', "Не удалось сохранить изменения<br>" . print_r($model->errors, true));
        }

        return $this->render('culture_create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws HttpException
     */
    public function actionCultureView($id)
    {
        $model = Culture::find()->multilingual()->where(['id' => $id])->one();

        if (empty($model)) {
            throw new HttpException(500);
        }

        return $this->render('culture_view', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws HttpException
     */
    public function actionCultureUpdate($id)
    {
        $model = Culture::find()->multilingual()->where(['id' => $id])->one();

        if (empty($model)) {
            throw new HttpException(500);
        }

        if ($model->load(\Yii::$app->request->post())) {

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', "Данные внесены");

                return $this->refresh();
            }

            \Yii::$app->session->setFlash('error', "Не удалось сохранить изменения<br>" . print_r($model->errors, true));
        }


        return $this->render('culture_update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws HttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionCultureDelete($id)
    {
        $model = Culture::findOne($id);

        if (empty($model)) {
            throw new HttpException(500);
        }

        if (empty($model->petroglyphs)) {
            $model->delete();
        } else {
            \Yii::$app->session->setFlash('error', 'Невозможно удалить, так как к нему использвется в петроглифах');
        }

        return $this->redirect(['manager/culture']);
    }

    /**
     * @return string
     */
    public function actionEpoch()
    {
        $query = Epoch::find();
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);

        return $this->render('epoch_list', ['provider' => $provider]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionEpochCreate()
    {
        $model = new Epoch();

        if ($model->load(\Yii::$app->request->post())) {

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', "Данные внесены");

                return $this->redirect(['manager/epoch-view', 'id' => $model->id]);
            }

            \Yii::$app->session->setFlash('error', "Не удалось сохранить изменения<br>" . print_r($model->errors, true));
        }

        return $this->render('epoch_create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws HttpException
     */
    public function actionEpochView($id)
    {
        $model = Epoch::find()->multilingual()->where(['id' => $id])->one();

        if (empty($model)) {
            throw new HttpException(500);
        }

        return $this->render('epoch_view', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws HttpException
     */
    public function actionEpochUpdate($id)
    {
        $model = Epoch::find()->multilingual()->where(['id' => $id])->one();

        if (empty($model)) {
            throw new HttpException(500);
        }

        if ($model->load(\Yii::$app->request->post())) {

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', "Данные внесены");

                return $this->refresh();
            }

            \Yii::$app->session->setFlash('error', "Не удалось сохранить изменения<br>" . print_r($model->errors, true));
        }


        return $this->render('epoch_update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws HttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionEpochDelete($id)
    {
        $model = Epoch::findOne($id);

        if (empty($model)) {
            throw new HttpException(500);
        }

        if (empty($model->petroglyphs)) {
            $model->delete();
        } else {
            \Yii::$app->session->setFlash('error', 'Невозможно удалить, так как к нему использвется в петроглифах');
        }

        return $this->redirect(['manager/epoch']);
    }

    /**
     * @return string
     */
    public function actionMethod()
    {
        $query = Method::find();
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);

        return $this->render('method_list', ['provider' => $provider]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionMethodCreate()
    {
        $model = new Method();

        if ($model->load(\Yii::$app->request->post())) {

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', "Данные внесены");

                return $this->redirect(['manager/method-view', 'id' => $model->id]);
            }

            \Yii::$app->session->setFlash('error', "Не удалось сохранить изменения<br>" . print_r($model->errors, true));
        }

        return $this->render('method_create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws HttpException
     */
    public function actionMethodView($id)
    {
        $model = Method::find()->multilingual()->where(['id' => $id])->one();

        if (empty($model)) {
            throw new HttpException(500);
        }

        return $this->render('method_view', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws HttpException
     */
    public function actionMethodUpdate($id)
    {
        $model = Method::find()->multilingual()->where(['id' => $id])->one();

        if (empty($model)) {
            throw new HttpException(500);
        }

        if ($model->load(\Yii::$app->request->post())) {

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', "Данные внесены");

                return $this->refresh();
            }

            \Yii::$app->session->setFlash('error', "Не удалось сохранить изменения<br>" . print_r($model->errors, true));
        }


        return $this->render('method_update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws HttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionMethodDelete($id)
    {
        $model = Method::findOne($id);

        if (empty($model)) {
            throw new HttpException(500);
        }

        if (empty($model->petroglyphs)) {
            $model->delete();
        } else {
            \Yii::$app->session->setFlash('error', 'Невозможно удалить, так как к нему использвется в петроглифах');
        }

        return $this->redirect(['manager/method']);
    }

    /**
     * @return string
     */
    public function actionPetroglyph()
    {
        $query = Petroglyph::find();

        if (!\Yii::$app->request->get('showDeleted')) {
            $query->where(['deleted' => null]);
        }

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);

        return $this->render('petroglyph_list', ['provider' => $provider]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionPetroglyphCreate()
    {
        $model = new Petroglyph();

        $cultures = ArrayHelper::map(Culture::find()->all(), 'id', 'name');
        $epochs = ArrayHelper::map(Epoch::find()->all(), 'id', 'name');
        $methods = ArrayHelper::map(Method::find()->all(), 'id', 'name');

        if ($model->load(\Yii::$app->request->post())) {

            if ($model->save()) {
                $model->fileImage = UploadedFile::getInstance($model, 'fileImage');
                $model->upload();
                \Yii::$app->session->setFlash('success', "Данные внесены");

                return $this->redirect(['manager/petroglyph-view', 'id' => $model->id]);
            }

            \Yii::$app->session->setFlash('error', "Не удалось сохранить изменения<br>" . print_r($model->errors, true));
        }

        return $this->render('petroglyph_create', [
            'model' => $model,
            'cultures' => $cultures,
            'epochs' => $epochs,
            'methods' => $methods,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws HttpException
     */
    public function actionPetroglyphView($id)
    {
        $model = Petroglyph::find()->multilingual()->where(['id' => $id])->one();

        if (empty($model)) {
            throw new HttpException(500);
        }

        $query = PetroglyphImage::find();

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);

        return $this->render('petroglyph_view', [
            'model' => $model,
            'provider' => $provider,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws HttpException
     */
    public function actionPetroglyphUpdate($id)
    {
        $model = Petroglyph::find()->multilingual()->where(['id' => $id])->one();

        $cultures = ArrayHelper::map(Culture::find()->all(), 'id', 'name');
        $epochs = ArrayHelper::map(Epoch::find()->all(), 'id', 'name');
        $methods = ArrayHelper::map(Method::find()->all(), 'id', 'name');

        if (empty($model)) {
            throw new HttpException(500);
        }

        if ($model->load(\Yii::$app->request->post())) {

            if ($model->save()) {
                $model->fileImage = UploadedFile::getInstance($model, 'fileImage');
                $model->upload();
                \Yii::$app->session->setFlash('success', "Данные внесены");

                return $this->redirect(['manager/petroglyph-view', 'id' => $model->id]);
            }

            \Yii::$app->session->setFlash('error', "Не удалось сохранить изменения<br>" . print_r($model->errors, true));
        }


        return $this->render('petroglyph_update', [
            'model' => $model,
            'cultures' => $cultures,
            'epochs' => $epochs,
            'methods' => $methods,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws HttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionPetroglyphDelete($id)
    {
        $model = Petroglyph::find()->multilingual()->where(['id' => $id])->one();

        if (empty($model)) {
            throw new HttpException(500);
        }

        if (!empty($model->images)) {
            foreach ($model->images as $image) {
                $image->delete();
            }
        }

        $model->beforeDelete();
        $model->deleted = 1;
        $model->save();

        return $this->redirect(['manager/petroglyph']);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionPetroglyphImageCreate($id)
    {
        $model = new PetroglyphImage();

        $petroglyph = Petroglyph::findOne($id);

        if (empty($petroglyph)) {
            throw new HttpException(500);
        }

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->save()) {
                $model->fileImage = UploadedFile::getInstance($model, 'fileImage');
                $model->upload();
                \Yii::$app->session->setFlash('success', "Данные внесены");

                return $this->redirect(['manager/petroglyph-view', 'id' => $petroglyph->id]);
            }

            \Yii::$app->session->setFlash('error', "Не удалось сохранить изменения<br>" . print_r($model->errors, true));
        }

        return $this->render('petroglyph_image_create', [
            'model' => $model,
            'petroglyph' => $petroglyph,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws HttpException
     */
    public function actionPetroglyphImageView($id)
    {
        $model = PetroglyphImage::find()->multilingual()->where(['id' => $id])->one();

        if (empty($model)) {
            throw new HttpException(500);
        }

        return $this->render('petroglyph_image_view', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws HttpException
     */
    public function actionPetroglyphImageUpdate($id)
    {
        $model = PetroglyphImage::find()->multilingual()->where(['id' => $id])->one();

        if (empty($model)) {
            throw new HttpException(500);
        }

        if ($model->load(\Yii::$app->request->post())) {

            if ($model->save()) {
                $model->fileImage = UploadedFile::getInstance($model, 'fileImage');
                $model->upload();
                \Yii::$app->session->setFlash('success', "Данные внесены");

                return $this->redirect(['manager/petroglyph-image-view', 'id' => $model->id]);
            }

            \Yii::$app->session->setFlash('error', "Не удалось сохранить изменения<br>" . print_r($model->errors, true));
        }


        return $this->render('petroglyph_image_update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws HttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionPetroglyphImageDelete($id)
    {
        $model = PetroglyphImage::findOne($id);

        if (empty($model)) {
            throw new HttpException(500);
        }

        $petroglyph = $model->petroglyph;
        $model->delete();

        return $this->redirect(['manager/petroglyph-view', 'id' => $petroglyph->id]);
    }
}
