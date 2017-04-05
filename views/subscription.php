<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\AppHelper;
use yii\helpers\Url;

$this->title = 'Subscription';
?>

<div class="container">
    <div class="row">

        <?= Yii::$app->session->getFlash('success') ?>
        <?= Yii::$app->session->getFlash('error') ?>

        <div class="col-sm-12">

            <div class="panel panel-default">
                <div class="panel-body">
                    <?php
                    $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
                    ?>

                    <button name="subscribe" value="1" type="submit" class="btn btn-info btn-large">Subscribe</button>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>


        </div>


    </div>
</div>



