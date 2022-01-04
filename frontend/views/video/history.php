<?php
/* Asadbek Developer */
use yii\widgets\ListView;

echo ListView::widget([
    'dataProvider'=>$dataProvider,
    'itemView'=>'_video_item',
    'layout'=>'<div class="d-flex flex-wrap">{items}</div>{pager}',
    'itemOptions'=>[
        'tags'=>false,
    ],
]);
?>



