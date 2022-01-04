<?php

namespace frontend\controllers;



use Codeception\PHPUnit\Constraint\Page;
use common\models\Comment;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CommentController extends Controller
{
    public function behaviors()
    {
          return [
              'access' => [
                  'class' => AccessControl::class,
                  'only' => ['create','delete'],
                  'rules' => [
                      [
                          'allow' => true,
                          'roles' => ['@']
                      ]
                  ]
              ],
              'content'=>[
                  'class'=>ContentNegotiator::class,
                  'only'=>['create','delete','updated'],
                  'formats'=>[
                      'application/json'=>Response::FORMAT_JSON
                  ]
              ],
              'verb'=>[
                  'class'=>VerbFilter::class,
                  'actions'=>[
                      'delete'=>['POST'],
                      'updated'=>['POST']
                  ]
              ]
          ];
    }

    public function actionCreate()
    {
        $userId = \Yii::$app->user->id;
        $id = $_POST['id'];
        $message = $_POST['message'];
        $comment = new Comment();
        $comment->comment = $message;
        $comment->video_id = $id;
        $comment->created_by = $userId;
        if($comment->save())
        {
            return [
               'success'=>true,
               'comment'=>$this->renderPartial('@frontend/views/video/_comment_item',[
                   'model'=>$comment
               ])
            ];
        }
        else{
            return [
                'success'=>false,
                'errors'=>$comment->errors
            ];
        }
     }

    public function actionDelete($id)
    {
       $comment=$this->findModel($id);
       if($comment->created_by === \Yii::$app->user->id)
       {
           $comment->delete();
           return [
               'success'=>true,
           ];
       }
       throw new ForbiddenHttpException('Comment boshqa userga tegishli !!!');
    }
    protected function findModel($id)
    {
        $comment=Comment::findOne($id);
        if(!$comment)
        {
            throw new NotFoundHttpException('Comment does not exsist');
        }
        return $comment;
    }

    public function actionUpdated($id)
    {
        $comment=$this->findModel($id);
        if($comment->created_by === \Yii::$app->user->id)
        {
            $commentText=\Yii::$app->request->post('comment-edit');
            $comment->comment=$commentText;
            if($comment->save()) {
                return [
                    'success' => true,
                    'comment'=>$this->renderPartial('@frontend/views/video/_comment_item',[
                        'model'=>$comment
                    ])
                ];
            }
            else
            {
                return [
                    'success'=>false,
                    'errors'=>$comment->errors
                ];
            }
        }
        throw new ForbiddenHttpException('Comment boshqa userga tegishli !!!');

    }
}
