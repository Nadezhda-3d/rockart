<?php
namespace frontend\controllers;

use common\models\Petroglyph;
use Yii;
use yii\filters\AccessControl;

/**
 * Class MapController
 * @package frontend\controllers
 */
class MapController extends BaseController
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
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Petroglyph::find()->where(['deleted' => null]);

        if (!Yii::$app->user->can('manager')) {
            $query->andWhere(['public' => 1]);
        }

        $r = null;

        if ($r = Yii::$app->request->get('r')) {
            $query->join('LEFT JOIN', 'petroglyph_language', 'petroglyph_language.petroglyph_id = petroglyph.id')
                ->andWhere(['like', 'name', $r])->orderBy(['id' => SORT_ASC])->all();
        }

        $petroglyphs = $query->all();
        $array_petroglyphs = [];

        if (!empty($petroglyphs)) {
            foreach ($petroglyphs as $petroglyph) {
                $array_petroglyphs[] = [
                    'id' => $petroglyph->id,
                    'name' => $petroglyph->name,
                    'lat' => $petroglyph->lat,
                    'lng' => $petroglyph->lng,
                    'image' => Petroglyph::SRC_IMAGE . '/' . $petroglyph->thumbnailImage,
                ];
            }
        }
        $json_petroglyphs = json_encode($array_petroglyphs, JSON_UNESCAPED_UNICODE);

        $mapProvider = Yii::$app->request->get('mapProvider') == 'yandex' ? 'yandex' : 'google';

        return $this->render('index', [
            'json_petroglyphs' => $json_petroglyphs,
            'mapProvider' => $mapProvider,
            'r' => $r,
        ]);
    }
}
