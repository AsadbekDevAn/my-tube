<?php

namespace common\models;

use yii\web\NotFoundHttpException;

/**
 * This is the ActiveQuery class for [[Comment]].
 *
 * @see Comment
 */
class CommentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Comment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Comment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function videoId($videoId)
    {
        return $this->andWhere(['video_id'=>$videoId]);
    }

    public function latest()
    {
        return $this->orderBy(['created_at'=>SORT_DESC]);
    }
}
