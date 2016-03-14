<?php

namespace app\commands;

use yii\console\Controller,
    app\models\Tile,
    app\components\MathHelper;

class MapGeneratorController extends Controller
{
    
    const UNSETTED_BIOME_FACTOR = -1;
    
    /**
     *
     * @var Tile[][] 
     */
    private $tiles = [];
    
    public function actionIndex($fromX, $fromY, $width, $height)
    {
        $endX = $fromX+$width;
        $endY = $fromY+$height;
        
        echo "Starting generate map from [{$fromX}, {$fromY}] to [{$endX}, {$endY}]".PHP_EOL;
        
        $tilesCount = 0;
        for ($i = $fromX; $i <= $endX; $i++) {
            for ($j = $fromY; $j <= $endY; $j++) {
                $this->tiles[$i][$j] = Tile::getByCoords($i, $j);
                $tilesCount++;
            }
        }
        
        echo "Starting generate elevation".PHP_EOL;
        
        for ($i = $fromX; $i <= $endX; $i++) {
            for ($j = $fromY; $j <= $endY; $j++) {
                $this->tiles[$i][$j]->elevation = self::UNSETTED_BIOME_FACTOR;
            }
        }
        
        $this->diamondStep($this->tiles[$fromX][$fromY], $this->tiles[$endX][$endY], 'elevation');
        
        echo "{$tilesCount} tiles elevation generated.".PHP_EOL;
        echo "Elevation map:".PHP_EOL;
        for ($i = $fromX; $i <= $endX; $i++) {
            for ($j = $fromY; $j <= $endY; $j++) {
                echo sprintf("%'.02d ", $this->tiles[$i][$j]->elevation);
            }
            echo PHP_EOL;
        }
        
        for ($i = $fromX; $i <= $endX; $i++) {
            for ($j = $fromY; $j <= $endY; $j++) {
                if (!$this->tiles[$i][$j]->save()) {
                    print_r($this->tiles[$i][$j]->getErrors());
                    return;
                }
            }
        }
        echo "{$tilesCount} tiles saved.".PHP_EOL;
        
        
    }
    
    /**
     * 
     * @param Tile $leftTop
     * @param Tile $rightBottom
     * @param string $param
     */
    private function diamondStep(Tile &$leftTop, Tile &$rightBottom, $param = 'elevation')
    {
        /* @var $leftBottom Tile */
        $leftBottom = &$this->tiles[$leftTop->x][$rightBottom->y];
        
        /* @var $rightTop Tile */
        $rightTop = &$this->tiles[$rightBottom->x][$leftTop->y];
        
        if ($leftBottom->$param === self::UNSETTED_BIOME_FACTOR) {
            $leftBottom->$param = $this->randomValue($param);
        }        
        if ($rightTop->$param === self::UNSETTED_BIOME_FACTOR) {
            $rightTop->$param = $this->randomValue($param);
        }        
        if ($leftTop->$param === self::UNSETTED_BIOME_FACTOR) {
            $leftTop->$param = $this->randomValue($param);
        }        
        if ($rightBottom->$param === self::UNSETTED_BIOME_FACTOR) {
            $rightBottom->$param = $this->randomValue($param);
        }
        
        $middlePoint = $this->getMiddle($leftBottom, $rightTop);
        if (!$middlePoint->equals($leftBottom) && !$middlePoint->equals($leftTop) && !$middlePoint->equals($rightBottom) && !$middlePoint->equals($rightTop)) {
            $middleValue = round(($leftBottom->$param + $rightTop->$param + $leftTop->$param + $rightBottom->$param)/4);        
            $middlePoint->$param = $this->randomValue($param, $middleValue);
        }
        
        $leftPoint = $this->getMiddle($leftBottom, $leftTop);
        if (!$leftPoint->equals($leftBottom) && !$leftPoint->equals($leftTop) && !$leftPoint->equals($rightBottom) && !$leftPoint->equals($rightTop)) {
            $leftValue = round(($leftBottom->$param + $leftTop->$param)/2);        
            $leftPoint->$param = $this->randomValue($param, $leftValue);
        }
        
        $topPoint = $this->getMiddle($rightTop, $leftTop);
        if (!$topPoint->equals($leftBottom) && !$topPoint->equals($leftTop) && !$topPoint->equals($rightBottom) && !$topPoint->equals($rightTop)) {
            $topValue = round(($rightTop->$param + $leftTop->$param)/2);        
            $topPoint->$param = $this->randomValue($param, $topValue);
        }
        
        $rightPoint = $this->getMiddle($rightBottom, $rightTop);
        if (!$rightPoint->equals($leftBottom) && !$rightPoint->equals($leftTop) && !$rightPoint->equals($rightBottom) && !$rightPoint->equals($rightTop)) {
            $rightValue = round(($rightBottom->$param + $rightTop->$param)/2);        
            $rightPoint->$param = $this->randomValue($param, $rightValue);
        }
        
        $bottomPoint = $this->getMiddle($leftBottom, $rightBottom);
        if (!$bottomPoint->equals($leftBottom) && !$bottomPoint->equals($leftTop) && !$bottomPoint->equals($rightBottom) && !$bottomPoint->equals($rightTop)) {
            $bottomValue = round(($leftBottom->$param + $rightBottom->$param)/2);        
            $bottomPoint->$param = $this->randomValue($param, $bottomValue);
        }
                
        if (abs($rightBottom->x - $leftBottom->x) > 1 && abs($rightBottom->y - $leftTop->y) > 1) {
            $this->diamondStep($leftTop, $middlePoint, $param);
            $this->diamondStep($topPoint, $rightPoint, $param);
            $this->diamondStep($leftPoint, $bottomPoint, $param);
            $this->diamondStep($middlePoint, $rightBottom, $param);
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
                return Tile::ELEVATION_MIN;
            case 'temperature':
                return Tile::TEMPERATURE_MIN;
            case 'rainfall':
                return Tile::RAINFALL_MIN;
            case 'drainage':
                return Tile::DRAINAGE_MIN;
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
                return Tile::ELEVATION_MAX;
            case 'temperature':
                return Tile::TEMPERATURE_MAX;
            case 'rainfall':
                return Tile::RAINFALL_MAX;
            case 'drainage':
                return Tile::DRAINAGE_MAX;
        }        
    }
}
