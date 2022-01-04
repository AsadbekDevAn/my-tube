<?php

namespace backend\controllers;
use Yii;
use common\models\Video;
use yii\base\Security;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\image\Box;

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
                    'rules'=>[
                        [
                            'allow'=>true,
                            'roles'=>['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Video models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Video::find()->creator(Yii::$app->user->id)->latest(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'video_id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Video model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Video model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Video();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $sec=new Security();
                $name=$sec->generateRandomString(8);
                $model->video=UploadedFile::getInstance($model,'video');
                $model->video->saveAs(Yii::getAlias('@frontend').'/web/storage/video/'.$name.'.'.$model->video->extension);
                $model->title=$model->video->baseName;
                $model->video_name=$name.'.'.$model->video->extension;
                $model->video=null;
                $model->save();
                if($model->save()) {
                    return $this->redirect(['update', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Video model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldImg=$model->poster;
        $oldImgPath=Yii::getAlias('@frontend').'/web/storage/poster/'.$oldImg;
        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->img=UploadedFile::getInstance($model,'img');
            if($model->img)
            {

                $model->img=UploadedFile::getInstance($model,'img');
                $model->img->saveAs(Yii::getAlias('@frontend').'/web/storage/poster/'.$model->img->baseName.'.'.$model->img->extension);
                $model->poster=$model->img->baseName.'.'.$model->img->extension;
                if(file_exists($oldImg))
                {
                    unlink($oldImgPath);
                }
            }
            else
            {
                $model->poster=$oldImg;
            }
            $imgPath=Yii::getAlias('@frontend').'/web/storage/poster/'.$model->img->baseName.'.'.$model->img->extension;
            Image::resize($imgPath,1920,1080,false,['quality'=>80])->save();
            $model->img=null;
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Video model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Video model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Video the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Video::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
