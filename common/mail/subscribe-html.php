<?php

/* @var $channel \common\models\User */
/* @var $user \common\models\User  */
?>

<p>Hello <?php echo $channel->username ?></p>
<p>User <?php echo \common\helpers\Link::channelLink($user->username) ?> has subscribed </p>
<p>MyTube team</p>