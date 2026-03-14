<?php

namespace app\models;

use Yii;

class Homework extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'homework';
    }

    public function rules()
    {
        return [
            [['description'], 'default', 'value' => null],
            [['is_finished'], 'default', 'value' => 0],
            [['subject_id', 'title', 'due_date'], 'required'],
            [['user_id', 'subject_id', 'is_finished'], 'integer'],
            [['description'], 'string'],
            [['due_date'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subject::class, 'targetAttribute' => ['subject_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('app', 'ID'),
            'user_id'     => Yii::t('app', 'User ID'),
            'subject_id'  => Yii::t('app', 'Subject ID'),
            'title'       => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'due_date'    => Yii::t('app', 'Due Date'),
            'is_finished' => Yii::t('app', 'Is Finished'),
        ];
    }

    public function getSubject()
    {
        return $this->hasOne(Subject::class, ['id' => 'subject_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function find()
    {
        return new HomeworkQuery(get_called_class());
    }
}