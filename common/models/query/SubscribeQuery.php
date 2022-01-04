<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\Subscribe]].
 *
 * @see \common\models\Subscribe
 */
class SubscribeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\Subscribe[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Subscribe|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    public function findUser($channel_id,$userId)
    {
        return $this->andWhere([
            'channel_id'=>$channel_id,
            'user_id'=>$userId,
        ]);
    }
}
