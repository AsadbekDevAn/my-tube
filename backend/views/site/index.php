<?php

/* @var $lastVideo \common\models\Video */
/* @var $numberViews common\models\Video */
/* @var $subscribe \common\models\Subscribe */
?>

<div class="site-index d-flex">
    <div class="card m-2" style="width: 14rem;">
        <?php if ($lastVideo): ?>
            <div class="embed-responsive embed-responsive-16by9 mb-3">
                <video class="embed-responsive-item"
                       poster="<?php echo $lastVideo->getVideoPoster() ?>"
                       src="<?php echo $lastVideo->getVideoLink() ?>"></video>
            </div>
            <div class="card-body">
                <h6 class="card-title"><?php echo $lastVideo->title ?></h6>
                <p class="card-text">
                    Likes: <?php echo $lastVideo->getLikes()->count() ?><br>
                    Views: <?php echo $lastVideo->getViews()->count() ?>
                </p>
                <a href="<?php echo \yii\helpers\Url::to(['/video/update',
                    'id' => $lastVideo->id]) ?>"
                   class="btn btn-primary">
                    Edit
                </a>
            </div>
        <?php else: ?>
            <div class="card-body">
                You don't have uploaded videos yet
            </div>
        <?php endif; ?>
    </div>
    <div class="card m-2" style="width: 14rem;">
            <div class="card-body">
                <h6 class="card-title"> Total Views:</h6>
                <p class="card-text" style="font-size: 20px">
                    <?= $numberViews?>
                </p>
            </div>
    </div>
    <div class="card m-2" style="width: 14rem;">
        <div class="card-body">
            <h6 class="card-title">Latest Subscriber:</h6>
            <?php foreach ($subscribe as $subscribe ): ?>
               <div>
                    <?= $subscribe->user->username ?>
               </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
