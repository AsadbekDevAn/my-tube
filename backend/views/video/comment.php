<?php

/* @var $model \common\models\Comment */

use yii\bootstrap4\Html;

?>
<div class="d-flex justify-content-between " style="margin-top: 10px; width: 300px">
    <div>
        <div class="d-flex">
            <img class="mr-3 comment-avatar" src="/frontend/web/img/avatar.svg" alt="" style="width:30px">
            <?= Html::a($model->createdBy->username,['/channel/view','username'=>$model->createdBy->username],['class'=>'text-dark','style'=>['font-size'=>'30px','margin-left'=>'0px']])?>
        </div>
       <div>
           <h3 class="text-dark"><?= $model->comment ?></h3>
       </div>
    </div>
</div>
