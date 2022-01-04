<?php

use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $channel \common\models\User */
/** @var $dataProvider \common\models\Video */
?>

<?php Pjax::begin()?>
    <?php $userId=\Yii::$app->user->id ?>
    <div class="jumbotron">
        <h1 class="display-4"><?= $channel->username ?></h1>
        <a class="btn <?= $channel->isJoinBy($channel->id,$userId) ? 'btn-secondary' : 'btn-danger'?> btn-lg" href="<?= Url::to(['channel/join','id'=>$channel->id,'username'=>$channel->username])?>" role="button" data-method="post" data-pjax="1">Subscribe <i class="fas fa-bells"></i></a>
        <?php echo $channel->getSubscribe()->count() ?>
    </div>
    <?php echo ListView::widget([
                'dataProvider'=>$dataProvider,
                'itemView'=>'@frontend/views/video/_video_item',
                'layout'=>'<div class="d-flex flex-wrap">{items}</div>{pager}',
       ]);
    ?>
<?php  Pjax::end()?>