<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string|null $date
 * @property int|null $year
 * @property string $name
 * @property string|null $model
 * @property string|null $country
 * @property float|null $price
 * @property int|null $count
 * @property string|null $file
 * @property int|null $category_id
 *
 * @property Cart[] $carts
 * @property Category $category
 * @property ProductOrder[] $productOrders
 */
class Product extends \yii\db\ActiveRecord
{
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['year', 'count', 'category_id'], 'integer'],
            [['name'], 'required'],
            [['price'], 'number'],
            [['name', 'model', 'country', 'file'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload()
    {
        $this->file = $this->imageFile->baseName . '.' . $this->imageFile->extension;
        if ($this->validate()) {
            $this->imageFile->saveAs('../web/uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            $this->save(false);
            return true;
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Дата',
            'year' => 'Год выпуска',
            'name' => 'Название',
            'model' => 'Модель',
            'country' => 'Страна-производитель',
            'price' => 'Цена',
            'count' => 'Количество',
            'file' => 'Изображение',
            'category_id' => 'Категория',
            'imageFile' => '',
        ];
    }

    /**
     * Gets query for [[Carts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarts()
    {
        return $this->hasMany(Cart::className(), ['product_id' => 'id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[ProductOrders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductOrders()
    {
        return $this->hasMany(ProductOrder::className(), ['product_id' => 'id']);
    }
}
