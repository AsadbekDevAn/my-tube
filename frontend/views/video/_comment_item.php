<?php
/* @var $model \common\models\Comment */
/* @var $video \common\models\Video */

use yii\helpers\Url;
?>
<div class="media comment-media">
    <img src="<?= '/img/avatar.svg'?>" class="avatar mr-3" alt="user photo ">
    <div class="media-body mt-3">
        <h5 class="mt-0">
            <?= $model->createdBy->username?>
            <small class="text-muted">
                <?php echo \Yii::$app->formatter->asRelativeTime($model->created_at)?>
                <?php  if($model->created_at !== $model->updated_at): ?>
                (edited)
                <?php endif; ?>
            </small>
        </h5>
        <div>
            <div class="text-wrapper">
                <?= $model->comment ?>
            </div>
            <div class="action-reply-button mt-2">
                <button class="btn btn-sm btn-light" style="border:0.5px solid #676464"><a href="" style="text-decoration: none;">REPLY</a></button>
            </div>
            <form class="comment-edit-text" action="<?= Url::to(['/comment/updated','id'=>$model->id ])?>" method="post">
                    <textarea   rows="1"
                                class="form-control"
                                name="comment-edit"
                                placeholder="Add a public comment"><?php echo $model->comment?></textarea>
                    <div class="action-buttons text-right mt-2">
                        <button class="btn btn-light btn-cancel">Cancel</button>
                        <button class="btn btn-primary btn-save">Save</button>
                    </div>
            </form>
            <?php if ($model->created_by === \Yii::$app->user->id ):?>
            <div class="dropdown comment-drop">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    dots
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                        <?php if ($model->created_by == $video->created_by):?>
                            <a class="dropdown-item" href="#">Pin</a>
                        <?php endif;?>
                            <a class="dropdown-item item-comment-edit" href="#">Edit</a>
                            <a class="dropdown-item item-comment-delete" href="<?= Url::to(['/comment/delete','id'=>$model->id])?>">Delete</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
