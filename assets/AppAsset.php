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
    ];
    public $js = [
        'js/jquery-1.11.3.min.js',
        'js/fullscreen.js',
        'js/icheck.js',
        'js/jquery-dateFormat.min.js',
        '//vk.com/js/api/xd_connection.js',
        '//www.google.com/jsapi?autoload={\'modules\':[{\'name\':\'visualization\',\'version\':\'1\',\'packages\':[\'corechart\']}]}',
        '//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
    
}
