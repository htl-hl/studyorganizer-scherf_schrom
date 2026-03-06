<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RegistrationForm extends Model
{
    public $username;
    public $password;
    public $password_repeat;
    public $role;

    public function rules()
    {
        return [
            [['username', 'password', 'password_repeat', 'role'], 'required'],
            ['username', 'string', 'min' => 4, 'max' => 255],
            ['password', 'string', 'min' => 8],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => "Passwords don't match"],
            ['role', 'in', 'range' => ['student', 'teacher']],
        ];
    }

    public function register()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->setPassword($this->password);
            $user->role = $this->role;
            $user->generateAuthKey();
            return $user->save();
        }
        return false;
    }
}