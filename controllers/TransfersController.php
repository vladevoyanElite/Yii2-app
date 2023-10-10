<?php

namespace app\controllers;

use function app\components\dumpe;
use app\models\LoginForm;
use app\models\User;
use Yii;
use app\models\TransfersHistory;
use app\models\TransfersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\SignupForm;
use yii\widgets\ActiveForm;
use yii\web\Response;
/**
 * TransfersController implements the CRUD actions for TransfersHistory model.
 */
class TransfersController extends Controller
{
    public function beforeAction($event)
    {
        return parent::beforeAction($event);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TransfersHistory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $sentData = [];
        if(!Yii::$app->user->isGuest){
            $user = User::findOne(Yii::$app->user->id)->toArray();
            $sentData['user'] = $user;
        }
        $userModel = new LoginForm();
        $searchModel = new TransfersSearch();
        $transferModel = new TransfersHistory();
        $transfersCount = count($transferModel->find()->all());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $sentData['transfersCount'] = $transfersCount;
        $sentData['dataProvider'] = $dataProvider;
        $sentData['transferModel'] = $transferModel;
        $sentData['userModel'] = $userModel;

        return $this->render('index',['sentData' => $sentData]);
    }

    /**
     * Displays a single TransfersHistory model.
     * @param integer $id
     * @return mixed
     */

    public function actionCreate()
    {
        $transferModel = new TransfersHistory();
        $userModel = new LoginForm();

        if($transferModel->load(Yii::$app->request->post()) && $userModel->load(Yii::$app->request->post())){

            $userId = Yii::$app->user->id;
            $transferModel->user_id = $userId;
            $username = Yii::$app->request->post('LoginForm')['username'];
            $sum = Yii::$app->request->post('TransfersHistory')['sum'];

            $user = User::findByUsername($username);

            if(is_null($user)){
                $signUp = new SignupForm();
                $userReceived = $signUp->signup($username);
            }else{
                $userReceived = $user;
            }

            if($userReceived['id'] == $userId){
                if (Yii::$app->request->isAjax) {
                    $userModel->addError('username','rrr');
                }

            }
            $transferModel->user_received_transfer =  $userReceived['id'];
            $transferModel->transfer_time =  new \yii\db\Expression('NOW()');
            $transferModel->sum =  $sum ;

            if ( $transferModel->save()) {
                if($this->increaseUserBalance($userReceived['id'],$sum)){
                    Yii::$app->session->setFlash('balance_increased',"{$userReceived['username']} `s balance has increased by {$sum} units . ");
                };
                if($this->decreaseUserBalance($userId,$sum)){
                    Yii::$app->session->setFlash('balance_decreased',"Your balance has reduced by  $sum units .");
                }
                Yii::$app->session->setFlash('transfer_done',"Your transfer to {$userReceived['username']} with {$sum} units has successfully been done !");
            }


            return $this->redirect('/site/profile');
        }

        throw new NotFoundHttpException('The requested page does not exist.');

    }

    public function decreaseUserBalance($userId = null ,$amount)
    {
        $user = User::findOne($userId);
        if(!empty($user)){
            $userOldBalance = $user['balance'];
            $user->balance = $userOldBalance - $amount ;

            if($user->save()){
                return true;
            }

            return false;
        }
        throw new NotFoundHttpException('User not found.');
    }

    public function increaseUserBalance($userId = null ,$amount)
    {
        $user = User::findOne($userId);
        if(!empty($user)){
            $userOldBalance = $user['balance'];
            $user->balance = $userOldBalance + $amount ;

            if($user->save()){
                return true;
            }

            return false;
        }
        throw new NotFoundHttpException('User not found.');
    }

    /**
     * Finds the TransfersHistory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TransfersHistory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TransfersHistory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
