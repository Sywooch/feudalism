<?php

namespace app\commands;

use Yii,
    yii\console\Controller;

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
    
}
