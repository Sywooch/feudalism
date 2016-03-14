<?php

namespace app\controllers;

use app\controllers\Controller;

/**
 * Description of MapController
 *
 * @author i.gorohov
 */
class MapController extends Controller {
    
    public function actionIndex()
    {
        
        return $this->render('default');
    }
}
