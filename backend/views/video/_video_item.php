<?php
/**
 * User: TheCodeholic
 * Date: 4/17/2020
 * Time: 11:23 AM
 */

/** @var $model \common\models\Video */

use \yii\helpers\StringHelper;
use yii\helpers\Url;

?>

<div class="media">
    <a href="<?php echo Url::to(['/video/update', 'id' => $model->id]) ?>">
        <div class="embed-responsive embed-responsive-16by9 mr-2"
             style="width: 120px">
            <video class="embed-responsive-item"
                   poster="<?php echo $model->getVideoPoster() ?>"
                   src="<?php echo $model->getVideoLink() ?>"
            </video>
        </div>
    </a>
    <div class="media-body">
        <h6 class="mt-0"><?php echo substr($model->title,0,15).'...'; ?></h6>
        <?php echo StringHelper::truncateWords($model->description, 10) ?>
    </div>
</div>
