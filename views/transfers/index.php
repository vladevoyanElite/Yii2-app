<?php

use yii\helpers\Html;
use yii\grid\GridView;
use fedemotta\datatables\DataTables;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transfers History';
$this->params['breadcrumbs'][] = $this->title;
//\app\components\dumpe($dataProvider);
?>
<div class="transfers-history-index">


    <div class="container">
        <h3 >Here You can see all users transfers .</h3>
        <br>
    </div>

    <div class="col-xs-12">
        <?php if(!empty($sentData['transfersCount'])) : ?>
            <?= DataTables::widget([
                'dataProvider' => $sentData['dataProvider'],
                'rowOptions'=>function($data){
                    if(!Yii::$app->user->isGuest){
                        if(Yii::$app->user->id == $data->getUser()->select('id')->scalar()){
                            return ['class' => 'danger'];
                        }
                        if(Yii::$app->user->id == $data->getReceivedUser()->select('id')->scalar()){
                            return ['class' => 'success'];
                        }
                    }

                },
                'columns' => [
                    [
                        'attribute' => 'Transfer user nickname',
                        'headerOptions' => [
                            'class'=>'custom_field_head text-center',
                        ],
                        'contentOptions' => [
                            'class' =>'text-center'
                        ],
                        'value' => function($data) {
                            return $data->getUser()->select('username')->scalar() ;
                        },
                    ],
                    [
                        'attribute' => 'Transaction received user nickname',
                        'headerOptions' => [
                            'class'=>'custom_field_head text-center',
                        ],
                        'contentOptions' => [
                            'class' =>'text-center'
                        ],
                        'value' => function($data) {
                            return $data->getReceivedUser()->select('username')->scalar();
                        },
                    ],
                    [
                        'attribute' => 'transfer_time',
                        'headerOptions' => [
                            'class'=>'custom_field_head text-center',
                        ],
                        'contentOptions' => [
                            'class' =>'text-center'
                        ],
                        'value' => function($data) {
                            return $data->transfer_time ;
                        },
                    ],
                    [
                        'attribute' => 'sum',
                        'format' => 'html',
                        'headerOptions' => [
                            'class'=>'custom_field_head text-center',
                        ],
                        'contentOptions' => [
                            'class' =>'text-center'
                        ],
                        'value' => function($data) {
                            return Yii::$app->user->id == $data->getUser()->select('id')->scalar() ? "<strong class='text-danger'>".$data->sum."</strong>" :  "<strong class='text-success'>".$data->sum."</strong>"  ;
                        },
                    ],
                    // 'created_at',

                ],
            ]); ?>
        <?php endif ;?>
    </div>

</div>
