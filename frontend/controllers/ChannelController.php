<?php

namespace frontend\controllers;

use common\models\Subscribe;
use common\models\User;
use common\models\Video;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/* @var $channel User */
/* @var $datdProvider Video */
class ChannelController extends Controller
{

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access'=>[
                    'class'=>AccessControl::class,
                    'only'=>['join'],
                    'rules'=>[
                        [
                            'allow'=>true,
                            'roles'=>['@'],
                        ]
                    ],
                ],
            ]
        );
    }

     public function actionView($username)
     {
         $channel=$this->findChannel($username);
         $dataProvider=new ActiveDataProvider([
            'query'=>Video::find()->creator($channel->id)->published(),
         ]);
         return $this->render('view',[
             'channel'=>$channel,
             'dataProvider'=>$dataProvider,
         ]);
     }
     public function actionJoin($id,$username)
     {
         $userId=\Yii::$app->user->id;
         $channel=$this->findChannel($username);
         $joinChannel=Subscribe::find()->findUser($id,$userId)->one();
         if(!$joinChannel)
         {
             $joinChannel=new Subscribe();
             $joinChannel->channel_id=$id;
             $joinChannel->user_id=$userId;
             $joinChannel->created_at=time();
             $joinChannel->save();
             \Yii::$app->mailer->compose([
                 'html'=>'subscribe-html','text'=>'subscribe-text'
             ],[
                 'channel'=>$channel,
                 'user'=>\Yii::$app->user->identity
             ])
                 ->setFrom(\Yii::$app->params['senderEmail'])
                 ->setTo($channel->email)
                 ->setSubject('I congrutulate you with subscribe')
                 ->send();
         }
         else{
             $joinChannel->delete();
         }
         return $this->render('view',['channel'=>$channel]);
     }
     public function findChannel($username)
     {
        $channelExsist=User::findByUsername($username);
        if(!$channelExsist)
        {
            throw new NotFoundHttpException('go back');
        }
        return $channelExsist;
     }

}