<?php

namespace app\commands;

use yii\console\Controller,
    app\models\Tile,
    app\models\Biome,
    app\components\MathHelper;

class MapGeneratorController extends Controller
{
    
    const UNSETTED_BIOME_FACTOR = -1;
    
    /**
     *
     * @var Tile[][] 
     */
    private $tiles = [];
    private $tilesCount = 0;
    
    private $fromX;
    private $fromY;
    private $endX;
    private $endY;
        
    public function actionIndex($fromX, $fromY, $width, $height)
    {
        $this->fromX = $fromX;
        $this->fromY = $fromY;
        $this->endX = $fromX+$width;
        $this->endY = $fromY+$height;
        
        echo "Starting generate map from [{$this->fromX}, {$this->fromY}] to [{$this->endX}, {$this->endY}]".PHP_EOL;
        
        $this->tilesCount = ($width+1)*($height+1);
        
        for ($i = $this->fromX; $i <= $this->endX; $i++) {
            for ($j = $this->fromY; $j <= $this->endY; $j++) {
                $this->tiles[$i][$j] = Tile::getByCoords($i, $j);
            }
        }
        
        $this->generateAndPrint('elevation');
        $this->generateAndPrint('temperature');
        $this->generateAndPrint('rainfall');
        $this->generateAndPrint('drainage');
        
        $this->calcBiomes();
        
        $savedCount = 0;
        for ($i = $this->fromX; $i <= $this->endX; $i++) {
            for ($j = $this->fromY; $j <= $this->endY; $j++) {
                if ($this->tiles[$i][$j]->save()) {
                    $savedCount++;
                } else {
                    print_r($this->tiles[$i][$j]->getErrors());
                    return;
                }
                
                if ($savedCount%10 === 0) {
                    echo "{$savedCount}/{$this->tilesCount} tiles saved".PHP_EOL;
                }
            }
        }
        echo "{$this->tilesCount} tiles saved.".PHP_EOL;
        
        
    }
    
    /**
     * 
     * @param string $param
     */
    private function generateAndPrint($param = 'elevation')
    {
        
        echo "Starting generate {$param}".PHP_EOL;
        
        for ($i = $this->fromX; $i <= $this->endX; $i++) {
            for ($j = $this->fromY; $j <= $this->endY; $j++) {
                if ($this->tiles[$i][$j]->isNewRecord) {
                    $this->tiles[$i][$j]->biome->$param = self::UNSETTED_BIOME_FACTOR;
                }
            }
        }
        
        $this->generateMapFactor($this->tiles[$this->fromX][$this->fromY], $this->tiles[$this->endX][$this->endY], $param);
        
        echo "{$this->tilesCount} tiles {$param} generated.".PHP_EOL;
        echo "{$param} map:".PHP_EOL;
        for ($i = $this->fromX; $i <= $this->endX; $i++) {
            for ($j = $this->fromY; $j <= $this->endY; $j++) {
                echo sprintf("%'.02d ", $this->tiles[$i][$j]->biome->$param);
            }
            echo PHP_EOL;
        }
    }
    
    private function calcBiomes()
    {
        echo "Starting calculate biomes".PHP_EOL;
        for ($i = $this->fromX; $i <= $this->endX; $i++) {
            for ($j = $this->fromY; $j <= $this->endY; $j++) {
                $this->tiles[$i][$j]->biome->calc();
            }
        }
        echo "{$this->tilesCount} tiles biomes setted.".PHP_EOL;
        echo "Biomes map:".PHP_EOL;
        for ($i = $this->fromX; $i <= $this->endX; $i++) {
            for ($j = $this->fromY; $j <= $this->endY; $j++) {
                echo sprintf("%'.02d ", $this->tiles[$i][$j]->biome->id);
            }
            echo PHP_EOL;
        }
    }
    
    /**
     * 
     * @param Tile $leftTop
     * @param Tile $rightBottom
     * @param string $param
     */
    private function generateMapFactor(Tile &$leftTop, Tile &$rightBottom, $param = 'elevation')
    {
        /* @var $leftBottom Tile */
        $leftBottom = &$this->tiles[$leftTop->x][$rightBottom->y];
        
        /* @var $rightTop Tile */
        $rightTop = &$this->tiles[$rightBottom->x][$leftTop->y];
        
        if ($leftBottom->biome->$param === self::UNSETTED_BIOME_FACTOR) {
            $leftBottom->biome->$param = $this->randomValue($param);
        }        
        if ($rightTop->biome->$param === self::UNSETTED_BIOME_FACTOR) {
            $rightTop->biome->$param = $this->randomValue($param);
        }        
        if ($leftTop->biome->$param === self::UNSETTED_BIOME_FACTOR) {
            $leftTop->biome->$param = $this->randomValue($param);
        }        
        if ($rightBottom->biome->$param === self::UNSETTED_BIOME_FACTOR) {
            $rightBottom->biome->$param = $this->randomValue($param);
        }
        
        /* @var $middlePoint Tile */
        $middlePoint = $this->getMiddle($leftBottom, $rightTop);
        if (!$middlePoint->equals($leftBottom) && !$middlePoint->equals($leftTop) && !$middlePoint->equals($rightBottom) && !$middlePoint->equals($rightTop)) {
            $middleValue = round(($leftBottom->biome->$param + $rightTop->biome->$param + $leftTop->biome->$param + $rightBottom->biome->$param)/4);        
            $middlePoint->biome->$param = $this->randomValue($param, $middleValue);
        }
        
        /* @var $leftPoint Tile */
        $leftPoint = $this->getMiddle($leftBottom, $leftTop);
        if (!$leftPoint->equals($leftBottom) && !$leftPoint->equals($leftTop) && !$leftPoint->equals($rightBottom) && !$leftPoint->equals($rightTop)) {
            $leftValue = round(($leftBottom->biome->$param + $leftTop->biome->$param)/2);        
            $leftPoint->biome->$param = $this->randomValue($param, $leftValue);
        }
        
        /* @var $topPoint Tile */
        $topPoint = $this->getMiddle($rightTop, $leftTop);
        if (!$topPoint->equals($leftBottom) && !$topPoint->equals($leftTop) && !$topPoint->equals($rightBottom) && !$topPoint->equals($rightTop)) {
            $topValue = round(($rightTop->biome->$param + $leftTop->biome->$param)/2);        
            $topPoint->biome->$param = $this->randomValue($param, $topValue);
        }
        
        /* @var $rightPoint Tile */
        $rightPoint = $this->getMiddle($rightBottom, $rightTop);
        if (!$rightPoint->equals($leftBottom) && !$rightPoint->equals($leftTop) && !$rightPoint->equals($rightBottom) && !$rightPoint->equals($rightTop)) {
            $rightValue = round(($rightBottom->biome->$param + $rightTop->biome->$param)/2);        
            $rightPoint->biome->$param = $this->randomValue($param, $rightValue);
        }
        
        /* @var $bottomPoint Tile */
        $bottomPoint = $this->getMiddle($leftBottom, $rightBottom);
        if (!$bottomPoint->equals($leftBottom) && !$bottomPoint->equals($leftTop) && !$bottomPoint->equals($rightBottom) && !$bottomPoint->equals($rightTop)) {
            $bottomValue = round(($leftBottom->biome->$param + $rightBottom->biome->$param)/2);        
            $bottomPoint->biome->$param = $this->randomValue($param, $bottomValue);
        }
                
        if (abs($rightBottom->x - $leftBottom->x) > 1 && abs($rightBottom->y - $leftTop->y) > 1) {
            $this->generateMapFactor($leftTop, $middlePoint, $param);
            $this->generateMapFactor($topPoint, $rightPoint, $param);
            $this->generateMapFactor($leftPoint, $bottomPoint, $param);
            $this->generateMapFactor($middlePoint, $rightBottom, $param);
        }
    }
    
    /**
     * 
     * @param Tile $tile1
     * @param Tile $tile2
     * @return Tile
     */
    private function &getMiddle(Tile $tile1, Tile $tile2)
    {
        $x = round(($tile1->x + $tile2->x)/2);
        $y = round(($tile1->y + $tile2->y)/2);
        
        return $this->tiles[$x][$y];
    }
    
    /**
     * 
     * @param string $param
     * @param integer $around
     * @return integer 
     */
    private function randomValue($param = 'elevation', $around = null)
    {
        $min = $this->getParamMin($param);
        $max = $this->getParamMax($param);
        
        if (is_null($around)) {
            return mt_rand($min, $max);
        } else {
            $val = $around + MathHelper::multipleFudgeDice(5);
            if ($val < $min) {
                $val = $min;
            }
            if ($val > $max) {
                $val = $max;
            }
            return $val;
        }
    }
    
    /**
     * 
     * @param string $param
     * @return integer
     */
    private function getParamMin($param = 'elevation')
    {
        switch ($param) {
            case 'elevation':
                return Biome::ELEVATION_MIN;
            case 'temperature':
                return Biome::TEMPERATURE_MIN;
            case 'rainfall':
                return Biome::RAINFALL_MIN;
            case 'drainage':
                return Biome::DRAINAGE_MIN;
        }        
    }
    
    /**
     * 
     * @param string $param
     * @return integer
     */
    private function getParamMax($param = 'elevation')
    {
        switch ($param) {
            case 'elevation':
                return Biome::ELEVATION_MAX;
            case 'temperature':
                return Biome::TEMPERATURE_MAX;
            case 'rainfall':
                return Biome::RAINFALL_MAX;
            case 'drainage':
                return Biome::DRAINAGE_MAX;
        }        
    }
}
