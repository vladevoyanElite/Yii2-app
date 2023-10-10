<?php
use fedemotta\datatables\DataTables;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;

$this->title = "Profile ". Yii::$app->user->identity->username;
$this->params['breadcrumbs'][] = 'Profile';

?>
<div class="site-profile">
    <?php if(Yii::$app->session->hasFlash('transfer_done')){ ?>
        <div class="alert alert-success">
            <p><?=Yii::$app->session->getFlash('transfer_done')?></p>
        </div>
        <?php if(Yii::$app->session->hasFlash('balance_increased') && Yii::$app->session->hasFlash('balance_decreased')){ ?>
            <div class="alert alert-info">
                <p><?=Yii::$app->session->getFlash('balance_increased')?></p>
                <p><?=Yii::$app->session->getFlash('balance_decreased')?></p>
            </div>
    <?php
            }
        }
    ?>
    <div class="container">
        <h3 class="text-center">Here You can see all your profile details and transfers history</h3>
    </div>
    <div class="container">
        <div class="alert alert-info col-xs-12 text-center">
            <p>
                <span class="text-muted">Your Nick </span> <strong> <?=$user['username']?></strong>
            </p>
            <p>
                <span class="text-muted">Balance </span>  <strong> <?=$user['balance']?> units</strong>
            </p>

        </div>
    </div>

    <div class="col-xs-12">
        <?php if(!empty($transfersCount)) : ?>
            <?= DataTables::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'attribute' => 'Transaction with user',
                        'headerOptions' => [
                            'class'=>'custom_field_head text-center',
                        ],
                        'contentOptions' => [
                            'class' =>'text-center'
                        ],
                        'value' => function($data) {
                            return Yii::$app->user->id == $data->getUser()->select('id')->scalar() ? $data->getReceivedUser()->select('username')->scalar() : $data->getUser()->select('username')->scalar();
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

                    [
                        'attribute' => 'Transaction type',
                        'headerOptions' => [
                            'class'=>'custom_field_head text-center',
                        ],
                        'contentOptions' => [
                            'class' =>'text-center'
                        ],
                        'value' => function($data) {
                            return Yii::$app->user->id == $data->getUser()->select('id')->scalar() ? "Sent" : "Received" ;
                        },
                    ],

                    // 'created_at',

                ],
            ]); ?>
        <?php endif ;?>
    </div>
</div>
<div class="col-xs-12">
    <p>

        <?php echo
        Html::a('<span class="btn-label">Make a transfer</span>' ,
            ['site','7'],
            ['class'=>'btn btn-success answer-message',
                'title' => 'View Feed Comments',
                'data-toggle'=>'modal',
                'data-target'=>'#transferCreateModal',])
        ?>
    </p>
</div>
<?php
    yii\bootstrap\Modal::begin([
        'header' => '<h4 class="text-center">Transfer</h4>',

        'id'=>'transferCreateModal',
    ])
;?>
    <?php
        $form = ActiveForm::begin([
            'action'=>'/transfers/create'
        ]);
    ?>

        <?= $form->field($userModel, 'username')->textInput()->label('Type the user nickname you want to transfer') ?>

        <?= $form->field($transferModel, 'sum')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>
<?php
    yii\bootstrap\Modal::end();
?>

