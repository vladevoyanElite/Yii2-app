<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use fedemotta\datatables\DataTables;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <div class="container">
        <h3 >Here You can see all users  .</h3>
        <br>
    </div>

    <?= DataTables::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'username',
                'headerOptions' => [
                    'class'=>'custom_field_head text-center',
                ],
                'contentOptions' => [
                    'class' =>'text-center'
                ],
                'value' => function($data) {
                    return $data->username ;
                },
            ],
            [
                'attribute' => 'balance',
                'headerOptions' => [
                    'class'=>'custom_field_head text-center',
                ],
                'contentOptions' => [
                    'class' =>'text-center'
                ],
                'value' => function($data) {
                    return $data->balance ;
                },
            ],
            [
                'attribute' => 'lastActivity',
                'headerOptions' => [
                    'class'=>'custom_field_head text-center',
                ],
                'contentOptions' => [
                    'class' =>'text-center'
                ],
                'value' => function($data) {
                    return $data->lastActivity ;
                },
            ],

            // 'created_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=> '{view}'
            ],
        ],
    ]); ?>
</div>
