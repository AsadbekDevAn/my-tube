<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Videos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Video', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                    'attribute'=>'Video',
                     'content'=>function($data){
                        return $this->render('_video_item',['model'=>$data]);
                     }
            ],
            [
                    'attribute'=>'Status',
                    'value'=>function($data)
                    {
                        if($data->status==1)
                        {
                            return   'PUBLISHED';
                        }
                        else
                        {
                            return  'UN_PUBLISHED';
                        }
                    }
            ],
            'description:ntext',
            'tags',
            'poster',
            'created_by',
            [
                    'attribute'=>'Created_At',
                    'value'=>function($data)
                    {
                        return Yii::$app->formatter->asDatetime($data->created_at);
                    },
            ],

            [
                    'class' => 'yii\grid\ActionColumn',
                     'buttons'=>[
                          'update'=>function($url)
                          {
                              return \yii\bootstrap4\Html::a('Update',$url,['class'=>'btn btn-primary',]);
                          },
                          'delete'=>function($url)
                          {
                            return Html::a('Delete',$url,['class'=>'btn btn-danger','data-method'=>'post','data-confirm'=>'Seryoz ?']);
                          },
                     ],
                     'template'=>'{update}{delete}',

            ],
        ],
    ]); ?>


</div>
