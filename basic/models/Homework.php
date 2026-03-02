<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "homework".
 *
 * @property int $id
 * @property int $user_id
 * @property int $subject_id
 * @property string $title
 * @property string|null $description
 * @property string $due_date
 * @property int|null $is_finished
 *
 * @property Subject $subject
 * @property User $user
 */
class Homework extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'homework';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'default', 'value' => null],
            [['is_finished'], 'default', 'value' => 0],
            [['user_id', 'subject_id', 'title', 'due_date'], 'required'],
            [['user_id', 'subject_id', 'is_finished'], 'integer'],
            [['description'], 'string'],
            [['due_date'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subject::class, 'targetAttribute' => ['subject_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'subject_id' => Yii::t('app', 'Subject ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'due_date' => Yii::t('app', 'Due Date'),
            'is_finished' => Yii::t('app', 'Is Finished'),
        ];
    }

    /**
     * Gets query for [[Subject]].
     *
     * @return \yii\db\ActiveQuery|SubjectQuery
     */
    public function getSubject()
    {
        return $this->hasOne(Subject::class, ['id' => 'subject_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return HomeworkQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new HomeworkQuery(get_called_class());
    }

}
