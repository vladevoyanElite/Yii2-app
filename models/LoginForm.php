<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $balance;
    public $lastActivity;
    public $rememberMe = true;
    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        if(!Yii::$app->user->isGuest){
            return [
                ['username', 'required'],
                ['username', 'trim'],
                ['username', 'string', 'min' => 4, 'max' => 20],
                ['username', 'compare','compareValue'=>Yii::$app->user->identity->username,'operator'=>'!=','message'=>'You cannot transfer amount to yourself','on'=>'transfer'],
            ];
        }
        return [
            // username is required
            ['username', 'required'],
            ['username', 'trim'],
            ['username', 'string', 'min' => 4, 'max' => 20],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }
        if(is_null($this->_user)){
            $signup = new SignupForm();

            $this->_user = $signup->signup($this->username);
        }
//        echo "<pre>" ;var_dump($this->_user);exit();


        return $this->_user;
    }
}
