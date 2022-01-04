<?php
namespace common\helpers;

use yii\helpers\Html;
use yii\helpers\Url;

/* @my helper */
class Link
{
    public static function channelLink($user)
    {
       return \yii\helpers\Html::a($user,['/channel/view','username'=>$user],['class'=>'text-dark']);
    }
}