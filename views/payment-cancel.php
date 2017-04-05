<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\AppHelper;
use yii\helpers\Url;

$this->title = 'Payment Cancel';
?>
<div class="container">
    <div class="row">
        <div class="col-sm-8">
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php
                    echo 'Order ID ' . $response['order_id'] . ' with Tracking ID ' . $response['tracking_id'] . ' has been ' . $response['order_status'] . '. <br/>Response message:<br/> ' . $response['status_message']
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>




