<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string|null $img
 * @property string $owner_id
 * @property string|null $created_at
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                // 'createdAtAttribute' => 'c_time', //Change the name of the field
                'updatedAtAttribute' => false, //false if you do not want to record the creation time.
                'value' => new Expression('NOW()'), // Change the value
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'owner_id'], 'required'],
            [['title', 'description'], 'string'],
            [['created_at'], 'safe'],
            [['img', 'owner_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'img' => 'Img',
            'owner_id' => 'Owner ID',
            'created_at' => 'Created At',
        ];
    }
}
