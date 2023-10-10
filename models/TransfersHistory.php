<?php

namespace app\models;

use Yii;
use app\models\User;
/**
 * This is the model class for table "transfers_history".
 *
 * @property int $id
 * @property int $user_id
 * @property number $sum
 * @property string $transfer_time
 * @property int $user_received_transfer
 *
 * @property User $user
 */
class TransfersHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transfers_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'user_received_transfer','sum'], 'required'],
            [['user_id', 'user_received_transfer'], 'integer'],
            [['sum'], 'number','numberPattern' => '/^\s*[-+]?[0-9]*[,]?[0-9][0-9]?\s*$/', 'message' => 'Sum must be a number.It can be a whole  or real number (maximum 2 numbers after " ," )'],
            [['transfer_time'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User',
            'sum' => 'Sum',
            'transfer_time' => 'Transfer Time',
            'user_received_transfer' => 'User Received Transfer',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getReceivedUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_received_transfer']);
    }
}
