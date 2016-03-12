<?php

namespace app\controllers;

use app\controllers\MyController;

/**
 * Description of MapController
 *
 * @author i.gorohov
 */
class MapController extends MyController {
    
    public function actionIndex()
    {
        
        return $this->render('default');
    }
}
