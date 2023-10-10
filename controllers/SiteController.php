<?php

namespace app\controllers;

use function app\components\dumpe;
use app\models\TransfersHistory;
use app\models\TransfersSearch;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

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

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionProfile()
    {
        if(!Yii::$app->user->isGuest){
            $user = User::findOne(Yii::$app->user->id);
            $userModel = new LoginForm();
            $userModel->scenario = 'transfer';
            $searchModel = new TransfersSearch();
            $transferModel = new TransfersHistory();
            $transfersCount = count($transferModel->find()->where(['user_id'=>$user['id']])->orWhere(['user_received_transfer'=>$user['id']])->all());
            $dataProvider = $searchModel->searchForOneUser(Yii::$app->request->queryParams);

            return $this->render('profile',
                [
                    'transfersCount' => $transfersCount,
                    'dataProvider'=>$dataProvider,
                    'transferModel'=>$transferModel,
                    'userModel'=>$userModel,
                    'user' => $user
                ]
            );
        }

        $this->redirect('login');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if(!empty(Yii::$app->user->id)){
                $user = User::findOne(Yii::$app->user->id);
                if(!empty($user)){
                    $user->lastActivity = new \yii\db\Expression('NOW()');
                    $user->save();
                }
            }
            return $this->redirect('profile');
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        if(!empty(Yii::$app->user->id)){
            $user = User::findOne(Yii::$app->user->id);
            if(!empty($user)){
                $user->lastActivity = new \yii\db\Expression('NOW()');
                $user->save();
            }
        }

        Yii::$app->user->logout();

        return $this->redirect('login');
    }

}
