<?php
/**
 * User: TheCodeholic
 * Date: 4/17/2020
 * Time: 9:20 AM
 */

?>

<aside class="shadow pl-2 pt-3 mt-2">
        <?php echo \yii\bootstrap4\Nav::widget([
    'options' => [
        'class' => 'd-flex flex-column nav-pills',

    ],
    'encodeLabels'=>false,
    'items' => [
        [
            'label' => '<i class="fas fa-home" ></i><span>Home</span>',
            'url' => ['/video/index']
        ],
        [
            'label' => '<i class="fa fa-history" aria-hidden="true"></i><span>History</span>',
            'url' => ['/video/history']
        ],
    ]
]) ?>
</aside>
