<?php

namespace app\models;

use Yii,
    app\models\ActiveRecord,
    app\models\UnitGroup,
    app\models\Castle,
    app\models\Biome,
    yii\db\ActiveQuery,
    yii\base\Exception;

/**
 * This is the model class for table "tiles".
 *
 * @property integer $id
 * @property integer $x
 * @property integer $y
 * @property integer $titleId 
 * @property integer $biomeId
 * @property integer $elevation
 * @property integer $temperature
 * @property integer $rainfall
 * @property integer $drainage
 * 
 * @property string $biomeLabel
 * @property string $biomeCharacter
 * @property string $biomeColor
 *
 * @property Biome $biome
 * @property Castle[] $castles
 * @property Title $title
 * @property UnitGroup[] $unitGroups
 */
class Tile extends ActiveRecord
{
        
    // Чанки
    
    /**
     * Ширина чанка
     */    
    const CHUNK_WIDTH = 27;
    
    /**
     * Высота чанка
     */
    const CHUNK_HEIGHT = 15;
    
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
            [['x', 'y', 'biomeId'], 'required'],
            [['x', 'y', 'biomeId', 'titleId', 'elevation', 'temperature', 'rainfall', 'drainage'], 'integer'],
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
            'biomeId' => Yii::t('app', 'Biome ID'),
            'titleId' => Yii::t('app', 'Title ID'), 
            'elevation' => Yii::t('app', 'Elevation'),
            'temperature' => Yii::t('app', 'Temperature'),
            'rainfall' => Yii::t('app', 'Rainfall'),
            'drainage' => Yii::t('app', 'Drainage'),
            'biomeLabel' => Yii::t('app', 'Biome label'),
            'biomeCharacter' => Yii::t('app', 'Biome character'),
            'biomeColor' => Yii::t('app', 'Biome color')
        ];
    }

    public static function displayedAttributes($owner = false)
    {
        return [
            'id',
            'x',
            'y',
            'titleId',
            'biomeId',
            'biomeLabel',
            'biomeCharacter',
            'biomeColor',
            'elevation',
            'temperature',
            'rainfall',
            'drainage'
        ];
    }
        
    public function getBiomeLabel()
    {
        return $this->biome->label;
    }
        
    public function getBiomeCharacter()
    {
        return $this->biome->character;
    }
        
    public function getBiomeColor()
    {
        return $this->biome->color;
    }
    
    private $_biome = null;
    
    public function getBiome()
    {
        if (is_null($this->_biome)) {
            $this->_biome = new Biome([
                'id' => $this->biomeId,
                'temperature' => $this->temperature,
                'elevation' => $this->elevation,
                'rainfall' => $this->rainfall,
                'drainage' => $this->drainage
            ]);
        }
        
        return $this->_biome;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCastles()
    {
        return $this->hasMany(Castle::className(), ['tileId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnitGroups()
    {
        return $this->hasMany(UnitGroup::className(), ['tileId' => 'id']);
    }
    
    /**
     * Находит или создаёт новый обьект тайла по переданным координатам
     * @param integer $x
     * @param integer $y
     * @param integer $biomeId Const. biome type
     * @param boolean $save autosave after create
     * @return \self
     * @throws Exception
     */
    public static function getByCoords($x, $y, $biomeId = Biome::BIOME_UNDEFINED, $save = false)
    {        
        return self::findOrCreate(['x' => $x, 'y' => $y], ['biomeId' => $biomeId], [], $save);
    }
    
    /**
     * Сравнивает тайл по координатам
     * @param ActiveRecord $record
     * @return boolean
     */
    public function equals($record)
    {
        return $this->x === $record->x && $this->y === $record->y;
    }
       
    /**
     * 
     * @param integer $x
     * @param integer $y
     * @return ActiveQuery
     */
    public static function findByChunk($x, $y)
    {
        return self::find()
                ->where(['>=', 'x', $x*self::CHUNK_WIDTH])
                ->andWhere(['<', 'x', ($x+1)*self::CHUNK_WIDTH])
                ->andWhere(['>=', 'y', $y*self::CHUNK_HEIGHT])
                ->andWhere(['<', 'y', ($y+1)*self::CHUNK_HEIGHT]);
    }
    
    public function beforeSave($insert)
    {
        // Сохранение параметров биома
        $this->biomeId = $this->biome->id;
        $this->temperature = $this->biome->temperature;
        $this->elevation = $this->biome->elevation;
        $this->rainfall = $this->biome->rainfall;
        $this->drainage = $this->biome->drainage;
        
        return parent::beforeSave($insert);
    }

}
