<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property string|null $date
 * @property string|null $rejected_reason
 * @property int $user_id
 * @property int $status_id
 *
 * @property ProductOrder[] $productOrders
 * @property Status $status
 * @property User $user
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['rejected_reason'], 'string'],
            [['user_id'], 'required'],
            [['user_id', 'status_id'], 'integer'],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Дата',
            'rejected_reason' => 'Причина отклонения или другой комментарий',
            'user_id' => 'Заказчик',
            'status_id' => 'Статус',
        ];
    }

    /**
     * Gets query for [[ProductOrders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrders()
    {
        return $this->hasMany(ProductOrder::className(), ['order_id' => 'id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getOrderStatus()
    {
        switch ($this->status_id) {
            case 1:
                return 'Новый';
            case 2:
                return '<div class="text-success">Одобрено</div>';
            case 3:
                return '<div class="text-danger">Отклонено</div>';
        }
    }

    public function renew()
    {
        $this->status_id = 1;
        $this->save();
    }

    public function approve()
    {
        $this->status_id = 2;
        $this->save();
    }

    public function reject()
    {
        $this->status_id = 3;
        $this->save();
    }
}
