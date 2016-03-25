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
        'css/theme.css'
    ];
    public $js = [
        'js/request.js',
        'js/fullscreen.js',
        'js/icheck.js',
        'js/jquery-dateFormat.min.js',
        '//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
    
}
