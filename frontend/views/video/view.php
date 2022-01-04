<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var $model \common\models\Video */
/** @var $similarVideo \common\models\Video */
/** @var $comments \common\models\Comment */
/* @var $this \yii\web\View*/
?>
<?php $userId=\Yii::$app->user->id ?>
<div class="row">
    <div class="col-sm-8" style="margin-bottom:200px;">
        <!-- left bar -->
        <div class="embed-responsive embed-responsive-16by9">
            <video class="embed-responsive-item"
                poster="<?= $model->getVideoPoster() ?>"
                src="<?= $model->getVideoLink() ?>"
                controls>
           </video>
        </div>
        <div>
            <h6 style="margin-top: 20px;"><?php echo $model->title; ?></h6> 
        </div>
        <div class="d-flex justify-content-between">
            <div class="text-muted">
                <?php echo $model->getViews()->count()?> views| <?php echo Yii::$app->formatter->asDate($model->created_at)?>
            </div>
            <div class="likeButton">
                <?php  Pjax::begin(['id'=>'view','timeout'=>false,'clientOptions'=>['method'=>'POST']]);?>
                        <a href="<?php echo Url::to(['/video/like','id'=>$model->id]) ?>"  class="btn btn-sm <?php echo $model->islikedBy(\Yii::$app->user->id)? 'btn-outline-primary': 'btn-outline-secondary' ?>"  data-method="post" data-pjax="1" ><i class="fas fa-thumbs-up"></i> <?php echo $model->getLikes()->count()?> </a>
                        <a href="<?php echo Url::to(['/video/dislike','id'=>$model->id]) ?>"  class="btn btn-sm <?php echo $model->isDislikedBy(\Yii::$app->user->id)?'btn-outline-primary':'btn-outline-secondary' ?>"  data-method="post" data-pjax="1"><i class="fas fa-thumbs-down"></i><?php echo $model->getDislikes()->count()?> </a>
                <?php  Pjax::end()?>
            </div>
        </div>
        <div class="d-flex justify-content-between " style="margin-top: 10px">
            <div>
                <div class="d-flex">
                    <img class="mr-3 comment-avatar" src="/frontend/web/img/avatar.svg" alt="" style="width:30px">
                    <?= Html::a($model->createdBy->username,['/channel/view','username'=>$model->createdBy->username],['class'=>'text-dark','style'=>['font-size'=>'30px','margin-left'=>'0px']])?>
                </div>
                <div>
                    <p><?= $model->getSubscribes()->count() ?> Followers</p>
                </div>
            </div>
        </div>
        <div class="comment mt-2">
            <div class="create-comment">
                <div class="text-dark mb-3">
                    <span id="comment-count" style="font-size: 20px"><?php echo count($comments)?> </span><span style="font-size: 20px"> comments</span>
                </div>
                <div class="media">
                    <img class="avatar mr-3" src="<?= '../img/avatar.svg'?>"  alt="user photo">
                    <div class="media-body">
                        <form class="create-comment-form" action="<?= Url::to(['/comment/create'])?>" method="post">
                            <input type="hidden" name="video_id" value="<?= $model->id?>" id="video_id">
                            <textarea   rows="1"
                                        id="leave-comment"
                                        class="form-control"
                                        name="comment"
                                        placeholder="Add a public comment">
                            </textarea>
                            <div class="action-buttons text-right mt-2">
                                <button type="button" class="btn btn-light" id="cancel-comment">Cancel</button>
                                <button type="button" class="btn btn-primary" id="submit-comment">Comment</button>
                            </div>
                        </form>
                    </div>    
                </div>
            </div>
        </div>
        <div id="comment-wrapper" class="comment-wrapper">
                <?php foreach ($comments as $comment) {
                    echo $this->render('_comment_item', [
                        'model' => $comment,
                    ]);
                } ?>
        </div>      
        <!-- left bar -->
    </div>
    <div class="col-sm-4">
        <?php  foreach($similarVideo as $similarVideo):?>
            <div class="media mb-3" >
                <a href="<?= Url::to(['video/view','id'=>$similarVideo->id])?>" >
                    <div class="embed-responsive embed-responsive-16by9 video">
                        <video class="embed-responsive-item"
                            poster="<?= $similarVideo->getVideoPoster() ?>"
                            src="<?= $similarVideo->getVideoLink() ?>"
                        </video>
                    </div>
                </a>
                <div class="media-body">
                    <p class="m-0" style="font-size: 20px"><?= $similarVideo->title ?></p>
                    <div>
                        <h6 class="text-muted"><?= $similarVideo->getViews()->count()?> views </h6>
                        <p class="m-0 text-muted"><?= Yii::$app->formatter->asRelativeTime($similarVideo->created_at)?></p>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</div>



