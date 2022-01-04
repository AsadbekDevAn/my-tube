<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Video */

$this->title = 'Create Video';
$this->params['breadcrumbs'][] = ['label' => 'Videos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-create">



    <div class="d-flex flex-column justify-content-center align-items-center">
        <h1><?= Html::encode($this->title) ?></h1>
        <div class="upload-icon">
            <i class="fas fa-upload"></i>
        </div>
        <br>

        <p class="m-0">Drag and drop a file you want to upload
        <p>

        <p class="text-muted">Your video will be private until you publish it</p>

        <?php $form = \yii\bootstrap4\ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data']
        ]) ?>

        <?php echo $form->errorSummary($model) ?>
        <?php echo $form->field($model,'video')->fileInput(['class'=>'form-control']) ?>
        <?php echo Html::submitButton('Send',['class'=>'btn btn-primary'])?>
        <?php \yii\bootstrap4\ActiveForm::end() ?>
    </div>

</div>
