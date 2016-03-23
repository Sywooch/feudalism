<?php

namespace app\models;

use Yii,
    app\models\ActiveRecord,
    app\models\UnitGroup,
    app\models\Castle,
    yii\db\ActiveQuery,
    yii\base\Exception;

/**
 * This is the model class for table "tiles".
 *
 * @property integer $id
 * @property integer $x
 * @property integer $y
 * @property integer $biome
 * @property integer $elevation
 * @property integer $temperature
 * @property integer $rainfall
 * @property integer $drainage
 * 
 * @property string $biomeLabel
 * @property string $biomeCharacter
 * @property string $biomeColor
 *
 * @property Castle[] $castles
 * @property UnitGroup[] $unitGroups
 */
class Tile extends ActiveRecord
{
    
    // Типы биомов
    
    /**
     * Неопределённый тип биома
     */
    const BIOME_UNDEFINED = 0;
    
    /**
     * Абстрактная вода
     */
    const BIOME_WATER = 10;
    
    /**
     * Ледяной океан
     */
    const BIOME_ARCTIC_OCEAN = 11;
    
    /**
     * Умеренный океан
     */
    const BIOME_TEMPERATE_OCEAN = 12;
    
    /**
     * Тропический океан
     */
    const BIOME_TROPICAL_OCEAN = 13;
    
    /**
     * Песчаная пустыня
     */
    const BIOME_SAND_DESERT = 20;
    
    /**
     * Каменистая пустошь
     */
    const BIOME_ROCKY_WASTELAND = 30;
    
    /**
     * Бесплодные земли
     */
    const BIOME_BADLANDS = 40;
    
    /**
     * Ледник
     */
    const BIOME_GLACIER = 41;
    
    /**
     * Тундра
     */
    const BIOME_TUNDRA = 42;
    
    /**
     * Тайга
     */
    const BIOME_TAIGA = 43;
    
    /**
     * Абстрактные луга
     */
    const BIOME_GRASSLAND = 50;
    
    /**
     * Умеренные луга
     */
    const BIOME_TEMPERATE_GRASSLAND = 51;
    
    /**
     * Тропические луга
     */
    const BIOME_TROPICAL_GRASSLAND = 52;
    
    /**
     * Абстрактная саванна
     */
    const BIOME_SAVANNA = 60;
    
    /**
     * Умеренная саванна
     */
    const BIOME_TEMPERATE_SAVANNA = 61;
    
    /**
     * Тропическая саванна
     */
    const BIOME_TROPICAL_SAVANNA = 62;
    
    /**
     * Абстрактное болото
     */
    const BIOME_MARSH = 70;
    
    /**
     * Умеренное пресноводное болото
     */
    const BIOME_TEMPERATE_FRESHWATER_MARSH = 71;
    
    /**
     * Тропическое пресноводное болото
     */
    const BIOME_TROPICAL_FRESHWATER_MARSH = 72;
    
    /**
     * Абстрактное мелколесье
     */
    const BIOME_SHRUBLAND = 80;
    
    /**
     * Умеренное мелколесье
     */
    const BIOME_TEMPERATE_SHRUBLAND = 81;
    
    /**
     * Тропическое мелколесье
     */
    const BIOME_TROPICAL_SHRUBLAND = 82;
    
    /**
     * Абстрактные холмы
     */
    const BIOME_HILLS = 90;
    
    /**
     * Абстрактная топь
     */
    const BIOME_SWAMP = 100;
    
    /**
     * Умеренная пресноводная топь
     */
    const BIOME_TEMPERATE_FRESHWATER_SWAMP = 101;
    
    /**
     * Тропическая пресноводная топь
     */
    const BIOME_TROPICAL_FRESHWATER_SWAMP = 102;
    
    /**
     * Абстрактный лес
     */
    const BIOME_FOREST = 110;
    
    /**
     * Умеренный лиственный лес
     */
    const BIOME_TEMPERATE_BROADLEAF_FOREST = 111;
    
    /**
     * Умеренный хвойный лес
     */
    const BIOME_TEMPERATE_CONIFER_FOREST = 112;
    
    /**
     * Тропический сухой лиственный лес
     */
    const BIOME_TROPICAL_DRY_BROADLEAF_FOREST = 113;
    
    /**
     * Тропический влажный лиственный лес
     */
    const BIOME_TROPICAL_WET_BROADLEAF_FOREST = 114;
    
    /**
     * Тропический хвойный лес
     */
    const BIOME_TROPICAL_CONIFER_FOREST = 115;
    
    /**
     * Низкие горы
     */
    const BIOME_LOW_MOUNTAIN = 120;
    
    /**
     * Горы
     */
    const BIOME_MOUNTAIN = 130;
    
    /**
     * Высокие горы
     */
    const BIOME_HIGH_MOUNTAIN = 140;

    // Константы для генератора
    
    /**
     * Минимальная высота
     */
    const ELEVATION_MIN = 0;
    
    /**
     * Максиамальная высота
     */
    const ELEVATION_MAX = 400;
    
    /**
     * Минимальная температура
     */
    const TEMPERATURE_MIN = 0;
    
    /**
     * Максимальная температура
     */
    const TEMPERATURE_MAX = 99;
    
    /**
     * Минимальный уровень осадков
     */
    const RAINFALL_MIN = 0;
    
    /**
     * Максимальный уровень осадков
     */
    const RAINFALL_MAX = 100;
    
    /**
     * Минимальный дренаж
     */
    const DRAINAGE_MIN = 0;
    
    /**
     * Максимальный дренаж
     */
    const DRAINAGE_MAX = 100;
    
    // Чанки
    
    /**
     * Ширина чанка
     */    
    const CHUNK_WIDTH = 27;
    
    /**
     * Высота чанка
     */
    const CHUNK_HEIGHT = 15;
    
    const CHUNK_SIZE = 1;
    
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
            [['x', 'y', 'biome'], 'required'],
            [['x', 'y', 'biome', 'elevation', 'temperature', 'rainfall', 'drainage'], 'integer'],
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
            'biome' => Yii::t('app', 'Biome'),
            'elevation' => Yii::t('app', 'Elevation'),
            'temperature' => Yii::t('app', 'Temperature'),
            'rainfall' => Yii::t('app', 'Rainfall'),
            'drainage' => Yii::t('app', 'Drainage')
        ];
    }

    public static function displayedAttributes($owner = false)
    {
        return [
            'id',
            'x',
            'y',
            'biome',
            'biomeLabel',
            'biomeCharacter',
            'biomeColor',
            'elevation',
            'temperature',
            'rainfall',
            'drainage'
        ];
    }
    
    /**
     * 
     */
    public function biomeLabels()
    {
        return [
            self::BIOME_UNDEFINED => Yii::t('app','Undefined'),   
            self::BIOME_WATER => Yii::t('app','Water'),   
            self::BIOME_ARCTIC_OCEAN => Yii::t('app','Arctic ocean'),   
            self::BIOME_TEMPERATE_OCEAN => Yii::t('app','Temperate ocean'),   
            self::BIOME_TROPICAL_OCEAN => Yii::t('app','Tropical ocean'),   
            self::BIOME_SAND_DESERT => Yii::t('app','Sand desert'),   
            self::BIOME_ROCKY_WASTELAND => Yii::t('app','Rocky wasteland'),   
            self::BIOME_BADLANDS => Yii::t('app','Badlands'),   
            self::BIOME_GLACIER => Yii::t('app','Glacier'),   
            self::BIOME_TUNDRA => Yii::t('app','Tundra'),   
            self::BIOME_TAIGA => Yii::t('app','Taiga'),   
            self::BIOME_GRASSLAND => Yii::t('app','Grassland'),   
            self::BIOME_TEMPERATE_GRASSLAND => Yii::t('app','Temperate grassland'),   
            self::BIOME_TROPICAL_GRASSLAND => Yii::t('app','Tropical grassland'),   
            self::BIOME_SAVANNA => Yii::t('app','Savanna'),   
            self::BIOME_TEMPERATE_SAVANNA => Yii::t('app','Temperate savanna'),   
            self::BIOME_TROPICAL_SAVANNA => Yii::t('app','Tropical savanna'),   
            self::BIOME_MARSH => Yii::t('app','Marsh'),   
            self::BIOME_TEMPERATE_FRESHWATER_MARSH => Yii::t('app','Temperate freshwater marsh'),   
            self::BIOME_TROPICAL_FRESHWATER_MARSH => Yii::t('app','Tropical freshwater marsh'),   
            self::BIOME_SHRUBLAND => Yii::t('app','Shrubland'),   
            self::BIOME_TEMPERATE_SHRUBLAND => Yii::t('app','Temperate shrubland'),   
            self::BIOME_TROPICAL_SHRUBLAND => Yii::t('app','Tropical shrubland'),   
            self::BIOME_HILLS => Yii::t('app','Hills'),   
            self::BIOME_SWAMP => Yii::t('app','Swamp'),   
            self::BIOME_TEMPERATE_FRESHWATER_SWAMP => Yii::t('app','Temperate freshwater swamp'),   
            self::BIOME_TROPICAL_FRESHWATER_SWAMP => Yii::t('app','Tropical freshwater swamp'),   
            self::BIOME_FOREST => Yii::t('app','Forest'),   
            self::BIOME_TEMPERATE_BROADLEAF_FOREST => Yii::t('app','Temperate broadleaf forest'),   
            self::BIOME_TEMPERATE_CONIFER_FOREST => Yii::t('app','Temperate conifer forest'),   
            self::BIOME_TROPICAL_DRY_BROADLEAF_FOREST => Yii::t('app','Tropical dry broadleaf forest'),   
            self::BIOME_TROPICAL_WET_BROADLEAF_FOREST => Yii::t('app','Tropical wet broadleaf forest'),   
            self::BIOME_TROPICAL_CONIFER_FOREST => Yii::t('app','Tropcal conifer forest'),   
            self::BIOME_LOW_MOUNTAIN => Yii::t('app','Low mountain'),
            self::BIOME_MOUNTAIN => Yii::t('app','Mountain'),
            self::BIOME_HIGH_MOUNTAIN => Yii::t('app','High mountain'),  
        ];
    }
    
    public function getBiomeLabel()
    {
        return $this->biomeLabels()[$this->biome];
    }
    
    public function biomeCharacters()
    {
        return [
            self::BIOME_UNDEFINED => ' ',   
            self::BIOME_WATER => '≈',   
            self::BIOME_ARCTIC_OCEAN => '≈',   
            self::BIOME_TEMPERATE_OCEAN => '≈',   
            self::BIOME_TROPICAL_OCEAN => '≈',   
            self::BIOME_SAND_DESERT => '≈',   
            self::BIOME_ROCKY_WASTELAND => ',',   
            self::BIOME_BADLANDS => '√',   
            self::BIOME_GLACIER => '▒',   
            self::BIOME_TUNDRA => '∙',   
            self::BIOME_TAIGA => '↨',   
            self::BIOME_GRASSLAND => '.',   
            self::BIOME_TEMPERATE_GRASSLAND => '.',   
            self::BIOME_TROPICAL_GRASSLAND => '.',   
            self::BIOME_SAVANNA => '"',   
            self::BIOME_TEMPERATE_SAVANNA => '"',   
            self::BIOME_TROPICAL_SAVANNA => '"',   
            self::BIOME_MARSH => 'ⁿ',   
            self::BIOME_TEMPERATE_FRESHWATER_MARSH => 'ⁿ',   
            self::BIOME_TROPICAL_FRESHWATER_MARSH => 'ⁿ',   
            self::BIOME_SHRUBLAND => 'τ',   
            self::BIOME_TEMPERATE_SHRUBLAND => 'τ',   
            self::BIOME_TROPICAL_SHRUBLAND => 'τ',   
            self::BIOME_HILLS => 'n',   
            self::BIOME_SWAMP => '"',   
            self::BIOME_TEMPERATE_FRESHWATER_SWAMP => '"',   
            self::BIOME_TROPICAL_FRESHWATER_SWAMP => '"',   
            self::BIOME_FOREST => '♣',   
            self::BIOME_TEMPERATE_BROADLEAF_FOREST => '♣',   
            self::BIOME_TEMPERATE_CONIFER_FOREST => '↨',   
            self::BIOME_TROPICAL_DRY_BROADLEAF_FOREST => '♣',   
            self::BIOME_TROPICAL_WET_BROADLEAF_FOREST => 'Γ',   
            self::BIOME_TROPICAL_CONIFER_FOREST => '↨',   
            self::BIOME_LOW_MOUNTAIN => '⌂',   
            self::BIOME_MOUNTAIN => '▲',   
            self::BIOME_HIGH_MOUNTAIN => '▲'  
        ];
    }
    
    public function getBiomeCharacter()
    {
        return $this->biomeCharacters()[$this->biome];
    }
    
    public function biomeColors()
    {
        return [
            self::BIOME_UNDEFINED => '#ffffff',   
            self::BIOME_WATER => '#000080',   
            self::BIOME_ARCTIC_OCEAN => '#000080',   
            self::BIOME_TEMPERATE_OCEAN => '#000080',   
            self::BIOME_TROPICAL_OCEAN => '#000080',   
            self::BIOME_SAND_DESERT => '#FFFF00',   
            self::BIOME_ROCKY_WASTELAND => '#808000',   
            self::BIOME_BADLANDS => '#808000',   
            self::BIOME_GLACIER => '#00FFFF',   
            self::BIOME_TUNDRA => '#008080',   
            self::BIOME_TAIGA => '#00FF00',   
            self::BIOME_GRASSLAND => '#00FF00',   
            self::BIOME_TEMPERATE_GRASSLAND => '#00FF00',   
            self::BIOME_TROPICAL_GRASSLAND => '#00FF00',   
            self::BIOME_SAVANNA => '#00FF00',   
            self::BIOME_TEMPERATE_SAVANNA => '#00FF00',   
            self::BIOME_TROPICAL_SAVANNA => '#00FF00',   
            self::BIOME_MARSH => '#00FF00',   
            self::BIOME_TEMPERATE_FRESHWATER_MARSH => '#00FF00',   
            self::BIOME_TROPICAL_FRESHWATER_MARSH => '#00FF00',   
            self::BIOME_SHRUBLAND => '#00FF00',   
            self::BIOME_TEMPERATE_SHRUBLAND => '#00FF00',   
            self::BIOME_TROPICAL_SHRUBLAND => '#00FF00',   
            self::BIOME_HILLS => '#00FF00',   
            self::BIOME_SWAMP => '#008000',   
            self::BIOME_TEMPERATE_FRESHWATER_SWAMP => '#008000',   
            self::BIOME_TROPICAL_FRESHWATER_SWAMP => '#008000',   
            self::BIOME_FOREST => '#00FF00',   
            self::BIOME_TEMPERATE_BROADLEAF_FOREST => '#00FF00',   
            self::BIOME_TEMPERATE_CONIFER_FOREST => '#00FF00',   
            self::BIOME_TROPICAL_DRY_BROADLEAF_FOREST => '#00FF00',   
            self::BIOME_TROPICAL_WET_BROADLEAF_FOREST => '#00FF00',   
            self::BIOME_TROPICAL_CONIFER_FOREST => '#00FF00',   
            self::BIOME_LOW_MOUNTAIN => '#808080',   
            self::BIOME_MOUNTAIN => '#808080',   
            self::BIOME_HIGH_MOUNTAIN => '#C0C0C0'  
        ];
    }
    
    public function getBiomeColor()
    {
        return $this->biomeColors()[$this->biome];
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
     * @param integer $biome Const. biome type
     * @param boolean $save autosave after create
     * @return \self
     * @throws Exception
     */
    public static function getByCoords($x, $y, $biome = self::BIOME_UNDEFINED, $save = false)
    {        
        return self::findOrCreate(['x' => $x, 'y' => $y], ['biome' => $biome], [], $save);
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
     * Вычисляет подходящий по факторам биом
     */
    public function calcBiome()
    {
        // проверка высоты
        if ($this->elevation < 100) {
            // Вода
            $this->calcWaterBiome();
        } elseif ($this->elevation > 299) {
            // Горы
            $this->calcMountainBiome();
        } else {
            // middle elevation
            $this->calcMiddleElevationBiome();
        }
    }
    
    private function calcWaterBiome()
    {
        // TODO: проверку на соль для озёр
        if ($this->temperature < 30) {
            $this->biome = self::BIOME_ARCTIC_OCEAN;
        } elseif ($this->temperature < 70) {
            $this->biome = self::BIOME_TEMPERATE_OCEAN;
        } else {
            $this->biome = self::BIOME_TROPICAL_OCEAN;
        }
    }
    
    private function calcMountainBiome()
    {
        if ($this->elevation < 333) {
            $this->biome = self::BIOME_LOW_MOUNTAIN;
        } elseif ($this->elevation > 365) {
            $this->biome = self::BIOME_HIGH_MOUNTAIN;
        } else {
            $this->biome = self::BIOME_MOUNTAIN;
        }
    }
    
    private function calcMiddleElevationBiome()
    {
        if ($this->temperature < 10) {
            $this->biome = self::BIOME_GLACIER;
        } elseif ($this->temperature < 20) {
            $this->biome = self::BIOME_TUNDRA;
        } else {
            // проверка осадков
            if ($this->rainfall < 10) {
                $this->calcDesertBiome();
            } elseif ($this->rainfall < 66) {
                $this->calcNormalBiome();
            } else {
                $this->calcForest();
            }            
        }
    }
    
    private function calcDesertBiome()
    {
        // проверка дренажа
        if ($this->drainage < 33) {
            $this->biome = self::BIOME_SAND_DESERT;
        } elseif ($this->drainage < 50) {
            $this->biome = self::BIOME_ROCKY_WASTELAND;
        } else {
            $this->biome = self::BIOME_BADLANDS;
        }
    }
    
    private function calcNormalBiome()
    {
        if ($this->drainage < 50) {
            if ($this->rainfall < 20) {
                if ($this->temperature < 70) {
                    $this->biome = self::BIOME_TEMPERATE_GRASSLAND;
                } else {
                    $this->biome = self::BIOME_TROPICAL_GRASSLAND;
                }
            } elseif ($this->rainfall < 33) {                
                if ($this->temperature < 70) {
                    $this->biome = self::BIOME_TEMPERATE_SAVANNA;
                } else {
                    $this->biome = self::BIOME_TROPICAL_SAVANNA;
                }
            } else {
                $this->calcMarsh();
            }
        } else {
            $this->biome = self::BIOME_HILLS;
        }
    }
    
    private function calcMarsh()
    {
        if ($this->drainage < 33) {
            // marsh
            if ($this->temperature < 70) {
                $this->biome = self::BIOME_TEMPERATE_FRESHWATER_MARSH;
            } else {
                $this->biome = self::BIOME_TROPICAL_FRESHWATER_MARSH;
            }
        } else {
            // shrubland
            if ($this->temperature < 70) {
                $this->biome = self::BIOME_TEMPERATE_SHRUBLAND;
            } else {
                $this->biome = self::BIOME_TROPICAL_SHRUBLAND;
            }
        }
    }
    
    private function calcForest()
    {
        if ($this->drainage < 33) {
            // swamp
            if ($this->temperature < 70) {
                $this->biome = self::BIOME_TEMPERATE_FRESHWATER_SWAMP;
            } else {
                $this->biome = self::BIOME_TROPICAL_FRESHWATER_SWAMP;
            }
        } else {
            // forest
            if ($this->temperature < 30) {
                $this->biome = self::BIOME_TAIGA;
            } elseif ($this->temperature < 70) {
                if ($this->drainage < 50) {
                    $this->biome = self::BIOME_TEMPERATE_CONIFER_FOREST;
                } else {
                    $this->biome = self::BIOME_TEMPERATE_BROADLEAF_FOREST;
                }
            } else {
                if ($this->drainage < 40) {
                    $this->biome = self::BIOME_TROPICAL_CONIFER_FOREST;
                } elseif ($this->drainage < 70) {
                    $this->biome = self::BIOME_TROPICAL_DRY_BROADLEAF_FOREST;
                } else {
                    $this->biome = self::BIOME_TROPICAL_WET_BROADLEAF_FOREST;
                }
            }
        }
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

}
