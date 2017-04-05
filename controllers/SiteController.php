<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Url;

class SiteController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (
                $action->id == "payment-process" || $action->id == 'payment-cancel'
        ) {
            Yii::$app->controller->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
//            'error' => [
//                'class' => 'yii\web\ErrorAction',
//            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionSubscription()
    {
        $this->layout = 'site_main';

        $request = Yii::$app->request->bodyParams;

        if (isset($request['subscribe'])) {

            $redirectUrl = Url::to(['site/payment-process'], true);
            $cancelUrl = Url::to(['site/payment-cancel'], true);

            $params = [
                'tid' => time(),
                'merchant_id' => 12345,
                'order_id' => 14523,
                'amount' => 2500,
                'currency' => 'INR',
                'redirect_url' => $redirectUrl,
                'cancel_url' => $cancelUrl,
                'language' => 'EN',
            ];

            \app\components\Ccavenue::form($params, 'auto', 'test', 'websites');
        }

        return $this->render('subscription', [
        ]);
    }

    public function actionPaymentProcess()
    {
        $request = Yii::$app->request->bodyParams;

        //for live 
        //$workingKey = '*************************';
        
        //for websites
        $workingKey = '*************************';

        //for local
        //$workingKey = '*************************';
        
        $encResponse = $request["encResp"];
        $rcvdString = decrypt($encResponse, $workingKey);
        $order_status = "";
        $decryptValues = explode('&', $rcvdString);
        $dataSize = sizeof($decryptValues);

        $response = [];

        for ($i = 0; $i < $dataSize; $i++) {
            $information = explode('=', $decryptValues[$i]);
            $response[$information[0]] = $information[1];
        }

        if ($response['order_status'] === 'Success') {

            print_r($response);

            //Yii::$app->session->setFlash('success', '<br>Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.');
            //return $this->redirect(['subscription']);
        }
        else if ($response['order_status'] === "Aborted") {
            Yii::$app->session->setFlash('error', 'Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail <br/>');
            return $this->redirect(['subscription']);
        }
        else if ($response['order_status'] === "Failure") {
            Yii::$app->session->setFlash('error', "<br>Thank you for shopping with us.However,the transaction has been declined.");
            return $this->redirect(['subscription']);
        }
        else {
            Yii::$app->session->setFlash('error', "<br>Security Error. Illegal access detected");
            return $this->redirect(['subscription']);
        }
    }

    public function actionPaymentCancel()
    {
        $this->layout = 'site_main';

        $request = Yii::$app->request->bodyParams;

        //for live 
        //$workingKey = '*************************';
        
        //for demo server if any
        $workingKey = '*************************';

        //for local
        //$workingKey = '*************************';

        $encResponse = $request["encResp"];
        $rcvdString = decrypt($encResponse, $workingKey);
        $order_status = "";
        $decryptValues = explode('&', $rcvdString);
        $dataSize = sizeof($decryptValues);

        $response = [];

        for ($i = 0; $i < $dataSize; $i++) {
            $information = explode('=', $decryptValues[$i]);
            $response[$information[0]] = $information[1];
        }
        //debugPrint($response);

        return $this->render('payment-cancel', [
                    'response' => $response
        ]);
    }

}
