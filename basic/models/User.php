<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    private static $hardcodedUsers = [
        '100' => [
            'id'          => '100',
            'username'    => 'admin',
            'password'    => 'admin',
            'authKey'     => 'test100key',
            'accessToken' => '100-token',
            'role'        => 'admin',
        ],
    ];

    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['username', 'password_hash', 'auth_key', 'role'], 'required'],
            [['username', 'password_hash', 'role'], 'string', 'max' => 255],
            ['auth_key', 'string', 'max' => 32],
            ['username', 'unique'],
            ['role', 'in', 'range' => ['student', 'teacher', 'admin']],
        ];
    }

    public static function findIdentity($id)
    {
        if (isset(self::$hardcodedUsers[$id])) {
            return new HardcodedUser(self::$hardcodedUsers[$id]);
        }
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$hardcodedUsers as $user) {
            if ($user['accessToken'] === $token) {
                return new HardcodedUser($user);
            }
        }
        return null;
    }

    public static function findByUsername($username)
    {
        foreach (self::$hardcodedUsers as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new HardcodedUser($user);
            }
        }
        return static::findOne(['username' => $username]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function setPassword(string $password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
}


class HardcodedUser extends \yii\base\BaseObject implements IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;
    public $role;

    public static function findIdentity($id) { return null; }
    public static function findIdentityByAccessToken($token, $type = null) { return null; }
    public function getId() { return $this->id; }
    public function getAuthKey() { return $this->authKey; }
    public function validateAuthKey($authKey) { return $this->authKey === $authKey; }
    public function validatePassword($password) { return $this->password === $password; }
}