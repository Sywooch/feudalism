<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/square.css',
        'css/style.css',
        '//fonts.googleapis.com/css?family=Amatic+SC:400,700',
    ];
    public $js = [
        'js/jquery-1.11.3.min.js',
        'js/fullscreen.js',
        'js/icheck.js',
        'js/jquery-dateFormat.min.js',
        '//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
    
}
