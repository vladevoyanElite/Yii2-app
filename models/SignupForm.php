<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 15/09/2017
 * Time: 01:05
 */

namespace app\models;


use yii\base\Model;

class SignupForm extends Model
{

    public $username;

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup($username)
    {
        $user = new User();
        $user->username = $username;
        $user->lastActivity = new \yii\db\Expression('NOW()');

        return $user->save() ? $user : null;
    }

}