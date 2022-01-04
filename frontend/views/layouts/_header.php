<?php
/**
 * User: TheCodeholic
 * Date: 4/17/2020
 * Time: 9:20 AM
 */

use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\Url;

NavBar::begin([
    'brandLabel' =>Yii::$app->name,
//    'brandImage'=>'@frontend/web/img/avatar.png',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => ['class' => 'navbar-expand-lg navbar-light bg-light shadow-sm'],
    'innerContainerOptions' => [
        'class' => 'container-fluid'
    ]
]);
if (Yii::$app->user->isGuest) {
    $menuItems[] = [
        'label' => 'Signup', 'url' => ['/site/signup'],
        ];
    $menuItems[] = [
        'label' => 'Login', 'url' => ['/site/login']
    ];
} else {
    $menuItems[] = [
        'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
        'url' => ['/site/logout'],
        'linkOptions' => [
            'data-method' => 'post'
        ]
    ];
}
?>
    <nav class="navbar navbar-light bg-light">
        <form   action="<?= Url::to(['/video/search'])?>"
                class="form-inline" >
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="keyword">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
    </nav>
<?php
echo Nav::widget([
    'options' => ['class' => 'navbar-nav ml-auto'],
    'items' => $menuItems,
]);
NavBar::end();
