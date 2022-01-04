<?php

namespace frontend\controllers;

use common\models\Comment;
use common\models\VideoLike;
use common\models\VideoView;
use Yii;
use common\models\Video;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\View;

/**
 * VideoController implements the CRUD actions for Video model.
 */

class VideoController extends Controller
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
                    'only'=>['like','dislike','history'],
                    'rules'=>[
                        [
                           'allow'=>true,
                           'roles'=>['@'],
                        ]
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                        'like'=>['POST'],
                        'dislike'=>['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionIndex()
    {
        $dataProvider=new ActiveDataProvider([
            'query'=>Video::find()->published()->latest(),
        ]);
        return $this->render('index',['dataProvider'=>$dataProvider]);
    }

    public function actionView($id)
    {
        $this->layout='auth';
        $video=Video::findOne($id);
        $this->findVideo($id);
        $this->videoView($id);
        $similarVideo=Video::find()
            ->with('createdBy')
            ->byKeyboard($video->title)
            ->published()
            ->andWhere(['NOT',['id'=>$id]])
            ->limit(10)
            ->all();
        $comments=Comment::find()
            ->with(['createdBy'])
            ->videoId($id)
            ->latest()
            ->all();
        return $this->render('view',[
            'model'=>$video,
            'similarVideo'=>$similarVideo,
            'comments'=>$comments,
            ]);
    }

    /**
     * @action Like
     */
    public function actionLike($id)
    {
        $video=$this->findVideo($id);
        $userId=\Yii::$app->user->id;
        $checkLike=VideoLike::find()->userIdvideoId($userId,$id)->one();
        if(!$checkLike)
        {
            $this->saveVideoLike($id,$userId,VideoLike::TYPE_LIKE);
        }
        elseif($checkLike->type == VideoLike::TYPE_LIKE)
        {
            $checkLike->delete();
        }
        else{
            $checkLike->delete();
            $this->saveVideoLike($id,$userId,VideoLike::TYPE_LIKE);
        }
        return $this->render('view',['model'=>$video]);

    }

    /**
     * @action Dislike
     */
    public function actionDislike($id)
    {
        $video=$this->findVideo($id);
        $userId=\Yii::$app->user->id;
        $checkLike=VideoLike::find()->userIdvideoId($userId,$id)->one();
        if(!$checkLike)
        {
            $this->saveVideoLike($id,$userId,VideoLike::TYPE_DISLIKE);
        }
        elseif($checkLike->type == VideoLike::TYPE_DISLIKE)
        {
            $checkLike->delete();
        }
        else{
            $checkLike->delete();
            $this->saveVideoLike($id,$userId,VideoLike::TYPE_DISLIKE);
        }
        return $this->render('view',['model'=>$video]);

    }
    public function videoView($id)
    {
        $videoView=new VideoView();
        $videoView->video_id=$id;
        $videoView->user_id=\Yii::$app->user->id;
        $videoView->created_at=time();
        $videoView->save();
    }
    public function actionSearch($keyword)
    {

         $query=Video::find()
             ->with('createdBy')
             ->published()
             ->latest();
             if($keyword)
             {
                 $query->byKeyboard($keyword);
             }
             $dataProvider=new ActiveDataProvider([
                 'query'=>$query
             ]);

             return $this->render('search',[
                 'dataProvider'=>$dataProvider,
             ]);
    }
    public function actionHistory()
    {
        $userId=Yii::$app->user->id;
        $query=Video::find()
//            ->alias('v')
            ->innerJoin("(SELECT video_id, MAX(created_at) as max_date FROM video_view WHERE user_id=:userId GROUP BY video_id) video_view",'video_view.video_id = video.id',['userId'=>$userId])
            ->orderBy('video_view.max_date DESC');
        $dataProvider=new ActiveDataProvider([
           'query'=>$query,
        ]);
       return $this->render('history',['dataProvider'=>$dataProvider]);
    }
    protected function findVideo($id)
    {
        $video=Video::findOne($id);
        if(!$video)
        {
           throw new NotFoundHttpException('video does not exsist');
        }
        return $video;
    }
    protected function saveVideoLike($videoId,$userId,$type)
    {
        $videoLike = new VideoLike();
        $videoLike->video_id = $videoId;
        $videoLike->user_id = $userId;
        $videoLike->type = $type;
        $videoLike->created_at = time();
        $videoLike->save();
    }

}
