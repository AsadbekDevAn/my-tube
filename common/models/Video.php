<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "video".
 *
 * @property int $id
 * @property string $video_name
 * @property string $title
 * @property string|null $description
 * @property string|null $tags
 * @property string|null $poster
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $created_at
 *
 * @property User $createdBy
 */
class Video extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $video;
    public $img;
    const UN_PUBLISHED = 0;
    const PUBLISHED = 1;

    public static function tableName()
    {
        return 'video';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
            [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['video_name', 'title'], 'required'],
            [['description'], 'string'],
            [['status', 'created_by', 'created_at'], 'integer'],
            [['video_name', 'title', 'poster'], 'string', 'max' => 255],
            [['tags'], 'string', 'max' => 16],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'video_name' => 'Video Name',
            'title' => 'Title',
            'description' => 'Description',
            'tags' => 'Tags',
            'poster' => 'Poster',
            'status' => 'Status',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getViews()
    {
        return $this->hasMany(VideoView::class,['video_id'=>'id']);
    }
    public function getSubscribes()
    {
        return $this->hasMany(Subscribe::class,['channel_id'=>'created_by']);
    }
    public function getLikes()
    {
        return $this->hasMany(VideoLike::class,['video_id'=>'id'])->liked();
    }

    public function getDislikes()
    {
        return $this->hasMany(VideoLike::class,['video_id'=>'id'])->disliked();
    }
    public function getStatusLabels()
    {
        return [
            self::UN_PUBLISHED,
            self::PUBLISHED,
        ];
    }

    public function getVideoTitle()
    {
        return $this->title;
    }

    public function getVideoLink()
    {
        return '/frontend/web/storage/video/'.$this->video_name;
    }

    public function getVideoPoster()
    {
        return '/frontend/web/storage/poster/'.$this->poster;
    }
    public function author()
    {
        return $this->createdBy->username;
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\VideoQuery the active query used by this AR class.
     */

    public static function find()
    {
        return new \common\models\query\VideoQuery(get_called_class());
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $videoPath = Yii::getAlias('@frontend') . '/web/storage/video/' . $this->video_name;
        unlink($videoPath);
        $posterPath = Yii::getAlias('@frontend') . '/web/storage/poster/' . $this->poster;
        if (file_exists($posterPath)) {
            unlink($posterPath);
        }
    }

    public function isLikedBy($id)
    {
        return VideoLike::find()
            ->userIdvideoId($id,$this->id)
            ->liked()
            ->one();
    }

    public function isDislikedBy($id)
    {
        return VideoLike::find()
            ->userIdvideoId($id,$this->id)
            ->disliked()
            ->one();
    }
    public function isJoinBy($id,$userId)
    {
        return Subscribe::find()
            ->findUser($id,$userId)
            ->one();
    }

}