<?php
/* Asadbek Developer */
use yii\widgets\ListView;

$this->title='Search video|'.Yii::$app->name;
?>
<h1>Found Videos:</h1>
<?php
echo ListView::widget([
    'dataProvider'=>$dataProvider,
    'itemView'=>'_video_item',
    'layout'=>'<div class="d-flex flex-wrap">{items}</div>{pager}',
    'itemOptions'=>[
       'tags'=>false,
    ],
]);
?>
