<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string|null $patronymic
 * @property string $email
 * @property string $login
 * @property string $password
 * @property int $role_id
 *
 * @property Cart[] $carts
 * @property Order[] $orders
 * @property Role $role
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $password_repeat;
    public $rules;

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null current user auth key
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * @param string $authKey
     * @return bool|null if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'surname', 'email', 'login', 'password'], 'required'],
            [['name', 'surname', 'email', 'login', 'patronymic'], 'trim'],
            [['name', 'surname', 'patronymic'], 'match', 'pattern' => '/^[??-??????-???? -]*$/u', 'message' => '?????????????????? ???????????? ??????????????????, ???????????? ?? ????????'],
            [['login'], 'match', 'pattern' => '/^[a-zA-Z0-9-]*$/i', 'message' => '?????????????????? ???????????? ????????????????, ?????????? ?? ????????'],
            [['role_id'], 'integer'],
            [['name', 'surname', 'patronymic', 'email', 'login', 'password'], 'string', 'max' => 255],
            [['name', 'surname', 'patronymic', 'email', 'login'], 'string', 'min' => 2],
            [['password'], 'string', 'min' => 6],
            [['email'], 'unique'],
            ['email', 'email'],
            [['login'], 'unique'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role_id' => 'id']],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
            ['rules', 'compare', 'compareValue' => '1', 'message' => '???????????????????? ?????????????? ?????????????? ??????????????????????'],
            ['role_id', 'default', 'value' => '1'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '??????',
            'surname' => '??????????????',
            'patronymic' => '????????????????',
            'email' => '?????????????????????? ??????????',
            'login' => '??????????',
            'password' => '????????????',
            'password_repeat' => '???????????? ????????????',
            'role_id' => '????????',
            'rules' => '???????????????? ?? ?????????????????? ??????????????????????',
        ];
    }

    /**
     * Gets query for [[Carts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarts()
    {
        return $this->hasMany(Cart::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }

    /**
     * Finds user by login
     *
     * @param string $login
     * @return static|null
     */
    public static function findBylogin($login)
    {
        return User::findOne(['login' => $login]);
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->password = md5($this->password);
            return true;
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function isAdmin()
    {
        return $this->role->code === 'admin';
    }
}
