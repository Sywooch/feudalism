<?php

namespace app\commands;

use Yii,
    yii\console\Controller,
    app\models\Tile,
    app\models\holdings\City;

/**
 * 
 * Вносит в базу тайлы суши из политсимовских JSON
 * 
 * @property \yii\db\Connection $db
 * 
 */
class InsertTilesController extends Controller
{
    
    public function getDb()
    {
        return Yii::$app->db;
    }
    
    public function actionIndex()
    {
        $this->db->createCommand()->truncateTable('tiles')->execute();
        for ($i = 0; $i <= 33; $i++) {
            $data = json_decode(file_get_contents(Yii::$app->basePath.'/data/hexagons/part'.$i.'.json'));
            array_pop($data);
            echo "part$i loaded".PHP_EOL;
            $tiles = [];
            while ($tile = array_pop($data)) {
                if ($tile[5]) {
                    $tiles[] = [
                        'x' => $tile[0],
                        'y' => $tile[1],
                        'centerLat' => $tile[2],
                        'centerLng' => $tile[3],
                    ];
                }
            }
            $this->db->createCommand()->batchInsert('tiles', ['x','y','centerLat','centerLng'], $tiles)->execute();
            echo "part$i inserted".PHP_EOL;
        }
        
    }
    
    public function actionCities()
    {
        
        City::deleteAll(['protoId' => City::PROTOTYPE]);
        $citiesTiles = [];
        for ($i = 0; $i <= 33; $i++) {
            $data = json_decode(file_get_contents(Yii::$app->basePath.'/data/hexagons/part'.$i.'.json'));
            array_pop($data);
            echo "part$i loaded".PHP_EOL;
            $tiles = [];
            while ($tile = array_pop($data)) {
                if ($tile[7]) {
                    $cityId = (int)$tile[7];
                    if (isset($citiesTiles[$cityId])) {
                        $citiesTiles[$cityId][] = [(int)$tile[0],(int)$tile[1]];
                    } else {
                        $citiesTiles[$cityId] = [[(int)$tile[0],(int)$tile[1]]];
                    }
                }
            }
        }
        echo "Loaded tiles for ".count($citiesTiles)." cities".PHP_EOL;
        
        $data = json_decode(file_get_contents(Yii::$app->basePath.'/data/cities.json'));
        array_pop($data);
        
        foreach ($data as $city) {
            $cityId = (int)$city[0];
            if (!isset($citiesTiles[$cityId])) {
                continue;
            }
            $cityName = $city[1];
            $cityPop = (int)round($city[4]/100000);
            $tiles = $citiesTiles[$cityId];
            
            if (count($tiles) == 1) {
                $tile = $tiles[0];
            } else {
                $centerX = 0;
                $centerY = 0;
                foreach ($tiles as $cTile) {
                    $centerX += $cTile[0];
                    $centerY += $cTile[1];
                }
                $centerX /= count($tiles);
                $centerY /= count($tiles);
                
                $minD = INF;
                $tile = $tiles[0];
                foreach ($tiles as $cTile) {
                    $d = hypot($cTile[0]-$centerX, $cTile[1]-$centerY);
                    if ($d < $minD) {
                        $minD = $d;
                        $tile = $cTile;
                    }
                }
            }
            
            $tile = Tile::findByXY($tile[0], $tile[1]);
            if (is_null($tile)) {
                echo "City {$cityName} tile [{$tile[0]}x{$tile[1]}] not found".PHP_EOL;
                continue;
            }
            
            $city = new City([
                'tileId' => $tile->id,
                'name' => $cityName,
                'population' => $cityPop,
                'fortification' => 1,
                'quarters' => 1,
                'builded' => 0,
                'buildedUserId' => 0,
            ]);
            if ($city->save()) {
                echo "City {$cityName} saved".PHP_EOL;
            } else {
                echo "City {$cityName} save errors: ";
                var_dump($city->getErrors());
            }
        }
    }
    
}
