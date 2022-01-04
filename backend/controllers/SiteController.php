<?php

namespace backend\controllers;

use common\models\LoginForm;
use common\models\Subscribe;
use common\models\Video;
use common\models\VideoView;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $userId=Yii::$app->user->id;
        $lastVideo=Video::find()
            ->with('createdBy')
            ->creator($userId)
            ->orderBy(['created_at'=>SORT_DESC])
            ->limit(1)
            ->one();
        $numberView=VideoView::find()
            ->with('video')
            ->alias('vv')
//            ->innerJoin("(SELECT id FROM video WHERE created_by =::userId GROUP BY id) v",'v.id = vv.video_id ',['userId'=>$userId])
            ->innerJoin(Video::tableName().' v',
                'v.id =vv.video_id')
            ->andWhere(['v.created_by'=>$userId])
            ->count();
        $subscribe=Subscribe::find()
            ->with('user')
            ->andWhere(['channel_id'=>$userId])
            ->orderBy(['created_at'=>SORT_DESC])
            ->limit(3)
            ->all();

        return $this->render('index',[
            'lastVideo'=>$lastVideo,
            'numberViews'=>$numberView,
            'subscribe'=>$subscribe,
            ]);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'auth';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
