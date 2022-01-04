<?php

use yii\helpers\Url;

/** $model common/models/Video  */
?>
    <div class="card" style="width:18rem; margin: 25px;border: 1px solid rgb(0,0.2,0.5) ">
        <a href="<?php echo Url::to(['/video/view','id'=>$model->id])?>">
            <div class="embed-responsive embed-responsive-16by9 ">
                <video class="embed-responsive-item"
                       poster="<?php echo $model->getVideoPoster()?>"
                       src="<?php echo $model->getVideoLink() ?>"
                </video>
            </div>
        </a>
        <div class="card-body p-2">
            <h6 class="card-title m-0"><?php echo substr($model->getVideoTitle(),0,30)?></h6>
                <?php echo \common\helpers\Link::channelLink($model->createdBy->username)?>
            <p class="card-text text-muted m-0"><?php echo $model->getViews()->count() ?> views . <?=  Yii::$app->formatter->asRelativeTime($model->created_at) ?></p>
        </div>
    </div>
