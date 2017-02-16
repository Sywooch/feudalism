<?php

namespace app\models;

use Yii,
    app\models\ActiveRecord,
    app\models\UnitGroup,
    app\models\holdings\Holding,
    app\models\titles\Title;

/**
 * This is the model class for table "tiles".
 * 
 * @property integer $id
 * @property integer $x
 * @property integer $y
 * @property integer $titleId 
 * @property double $centerLat 
 * @property double $centerLng 
 * 
 * @property string $ownerName
 * @property array $coords
 *
 * @property Holding $holding
 * @property Title $title
 * @property UnitGroup[] $unitGroups
 */
class Tile extends ActiveRecord
{
    
    public $holdingId;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tiles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['x', 'y', 'centerLat', 'centerLng'], 'required'],
            [['x', 'y'], 'integer'],
            [['centerLat', 'centerLng'], 'number'],
            [['titleId'], 'integer', 'min' => 1],
            [['titleId'], 'exist', 'targetClass' => Title::className(), 'targetAttribute' => ['titleId' => 'id']],
            [['x', 'y'], 'unique', 'targetAttribute' => ['x', 'y'], 'message' => Yii::t('app','The combination of X and Y has already been taken.')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'x' => Yii::t('app', 'X'),
            'y' => Yii::t('app', 'Y'),
            'titleId' => Yii::t('app', 'Title ID'), 
            'centerLat' => Yii::t('app', 'Center latitude'),
            'centerLng' => Yii::t('app', 'Center longitude'),
        ];
    }

    public static function displayedAttributes($owner = false)
    {
        return [
            'id',
            'x',
            'y',
            'titleId',
            'centerLat',
            'centerLng',
            'coords',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHolding()
    {
        return $this->hasOne(Holding::className(), ['tileId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTitle()
    {
        return $this->hasOne(Title::className(), ['id' => 'titleId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnitGroups()
    {
        return $this->hasMany(UnitGroup::className(), ['tileId' => 'id']);
    }
        
    /**
     * Сравнивает тайл по координатам
     * @param Tile $record
     * @return boolean
     */
    public function equals($record)
    {
        return (int)$this->x === (int)$record->x && (int)$this->y === (int)$record->y;
    }
        
    public function getOwnerName()
    {
        return $this->title ? $this->title->userName : Yii::t('app', 'no owner');
    }
        
    public function getLatFactor()
    {
        return cos($this->centerLat*0.0175)*0.088765;
    }
    
    public function getCoords()
    {
        $latFactor = $this->latFactor;
        return [
            [$this->centerLat,$this->centerLng+0.1], # east
            [$this->centerLat-$latFactor,$this->centerLng+0.05], # east-south
            [$this->centerLat-$latFactor,$this->centerLng-0.05], # west-south
            [$this->centerLat,$this->centerLng-0.1], # west
            [$this->centerLat+$latFactor,$this->centerLng-0.05], # west-nord
            [$this->centerLat+$latFactor,$this->centerLng+0.05] # east-nord
        ];
    }
    
    public static function findByXY(int $x, int $y)
    {
        return static::find()->where(['x' => $x, 'y' => $y])->one();
    }

}
