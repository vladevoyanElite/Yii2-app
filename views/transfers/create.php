<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TransfersHistory */

$this->title = 'Create Transfers History';
$this->params['breadcrumbs'][] = ['label' => 'Transfers Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transfers-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
