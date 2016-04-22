<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%phone}}".
 *
 * @property integer $id
 * @property string $phone
 * @property integer $contact_id
 *
 * @property Contact $contact
 */
class Phone extends \yii\db\ActiveRecord
{
    const SCENARIO_TABULAR = 'tabular';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%phone}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contact_id'], 'required'],
            [['phone'], 'required', 'except' => self::SCENARIO_TABULAR],
            [['contact_id'], 'integer'],
            [['phone'], 'string', 'max' => 255],
            [['phone'], 'match', 'pattern'=>'/^\(\d{3}\)[ ]\d{3}[\-]\d{2}[\-]\d{2}$/'],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contact::className(), 'targetAttribute' => ['contact_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'phone' => Yii::t('app', 'Phone'),
            'contact_id' => Yii::t('app', 'Contact ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['id' => 'contact_id']);
    }
}
