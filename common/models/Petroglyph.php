<?php

namespace common\models;

use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;
use thamtech\uuid\helpers\UuidHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use Imagine\Image\Box;

/**
 * This is the model class for table "petroglyph".
 *
 * @property int $id
 * @property string $uuid
 * @property string $lat
 * @property string $lng
 * @property string $image
 * @property string $orientation_x
 * @property string $orientation_y
 * @property string $orientation_z
 * @property int $method_id
 * @property int $culture_id
 * @property int $epoch_id
 * @property int $deleted
 * @property int $public
 * @property int $created_at
 * @property int $updated_at
 *
 * @property string $name
 * @property string $name_en
 * @property string $description
 * @property string $description_en
 *
 * @property Culture $culture
 * @property Epoch $epoch
 * @property Method $method
 * @property PetroglyphImage[] $images
 * @property string $thumbnailImage
 */
class Petroglyph extends \yii\db\ActiveRecord
{

    const DIR_IMAGE = 'storage/web/petroglyph';
    const SRC_IMAGE = '/storage/petroglyph';
    const THUMBNAIL_W = 800;
    const THUMBNAIL_H = 500;
    const THUMBNAIL_PREFIX = 'thumbnail_';

    public $fileImage;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'petroglyph';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'name_en'], 'required'],
            [['name', 'name_en', 'description', 'description_en'], 'string'],
            [['lat', 'lng', 'orientation_x', 'orientation_y', 'orientation_z'], 'number'],
            [['method_id', 'culture_id', 'epoch_id', 'deleted', 'public'], 'integer'],
            [['uuid'], 'string', 'max' => 64],
            [['image'], 'string', 'max' => 255],
            [['culture_id'], 'exist', 'skipOnError' => true, 'targetClass' => Culture::className(), 'targetAttribute' => ['culture_id' => 'id']],
            [['epoch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Epoch::className(), 'targetAttribute' => ['epoch_id' => 'id']],
            [['method_id'], 'exist', 'skipOnError' => true, 'targetClass' => Method::className(), 'targetAttribute' => ['method_id' => 'id']],
            [['fileImage'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif'],
        ];
    }

    /**
     * @return MultilingualQuery|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return new MultilingualQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'ml' => [
                'class' => MultilingualBehavior::className(),
                'languages' => [
                    'ru' => 'Russian',
                    'en' => 'English',
                ],
                'languageField' => 'locale',
                'defaultLanguage' => 'ru',
                'langForeignKey' => 'petroglyph_id',
                'tableName' => "{{%petroglyph_language}}",
                'attributes' => [
                    'name',
                    'description',
                ]
            ],
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('model', 'Name in Russian'),
            'name_en' => Yii::t('model', 'Name in English'),
            'description' => Yii::t('model', 'Description in Russian'),
            'description_en' => Yii::t('model', 'Description in English'),
            'lat' => Yii::t('model', 'Latitude'),
            'lng' => Yii::t('model', 'Longitude'),
            'image' => Yii::t('model', 'Image'),
            'fileImage' => Yii::t('model', 'Image'),
            'method_id' => Yii::t('model', 'Method'),
            'culture_id' => Yii::t('model', 'Culture'),
            'epoch_id' => Yii::t('model', 'Epoch'),
            'public' => Yii::t('model', 'Published'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCulture()
    {
        return $this->hasOne(Culture::className(), ['id' => 'culture_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEpoch()
    {
        return $this->hasOne(Epoch::className(), ['id' => 'epoch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMethod()
    {
        return $this->hasOne(Method::className(), ['id' => 'method_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(PetroglyphImage::className(), ['petroglyph_id' => 'id']);
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function upload()
    {
        if ($this->validate() and !empty($this->fileImage)) {

            $path = self::basePath();

            if (!empty($this->image) and file_exists($path . '/' . $this->image)) {
                unlink($path . '/' . $this->image);

                if (file_exists($path . '/' . self::THUMBNAIL_PREFIX . $this->image)) {
                    unlink($path . '/' . self::THUMBNAIL_PREFIX . $this->image);
                }
            }

            FileHelper::createDirectory($path);

            $newName = md5(uniqid($this->id));
            $this->fileImage->saveAs($path . '/' . $newName . '.' . $this->fileImage->extension);
            $this->image = $newName . '.' . $this->fileImage->extension;

            $sizes = getimagesize($path . '/' . $newName . '.' . $this->fileImage->extension);
            if ($sizes[0] > self::THUMBNAIL_W) {
                $width = self::THUMBNAIL_W;
                $height = $width * $sizes[1] / $sizes[0];
                Image::thumbnail($path . '/' . $newName . '.' . $this->fileImage->extension, $width, $height)
                    ->resize(new Box($width, $height))
                    ->save($path . '/' . self::THUMBNAIL_PREFIX . $newName . '.' . $this->fileImage->extension, ['quality' => 80]);
            }

            $this->fileImage = false;
            return $this->save();
        } else {
            return false;
        }
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public function getThumbnailImage()
    {
        $path = self::basePath();

        if (file_exists($path . '/' . self::THUMBNAIL_PREFIX . $this->image)) {
            return self::THUMBNAIL_PREFIX . $this->image;
        } else {
            return $this->image;
        }
    }

    /**
     * Удаляем файл перед удалением записи
     *
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeDelete()
    {
        $baseDir = self::basePath();

        if (!empty($this->image) and file_exists($baseDir . '/' . $this->image)) {
            unlink($baseDir . '/' . $this->image);

            if (file_exists($baseDir . '/' . self::THUMBNAIL_PREFIX . $this->image)) {
                unlink($baseDir . '/' . self::THUMBNAIL_PREFIX . $this->image);
            }
        }

        return parent::beforeDelete();
    }

    /**
     * Устанавливает путь до директории
     *
     * @return string
     * @throws \yii\base\Exception
     */
    public static function basePath()
    {
        $path = \Yii::getAlias('@' . self::DIR_IMAGE);

        // Создаем директорию, если не существует
        FileHelper::createDirectory($path);

        return $path;
    }

    public function search($params)
    {
        $query = Petroglyph::find()->joinWith('petroglyph_language');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        return $dataProvider;
    }

    public function beforeSave($insert)
    {
        if (empty($this->uuid)) {
            $this->uuid = UuidHelper::uuid();
        }
        return parent::beforeSave($insert);
    }
}
