<?php

use yii\helpers\Html;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <!--[if IE]><link rel="shortcut icon" href="/favicon.ico"><![endif]-->
        <link rel="icon" href="/img/logo512.png">
    </head>
    <body>
        <?php $this->beginBody() ?>
        <img src="/img/ajax-loader.gif" id="spinner" style="display:none" alt="Загрузка..." >
        <div>
            <?= $content ?>
        </div>
        <footer class='footer'>
            <p>Разработка — <a href="http://lazzyteam.com" target="_blank">LazzyTeam</a></p>
        </footer>
        <?php $this->endBody() ?>
        <script>
        $(function(){
            (adsbygoogle = window.adsbygoogle || []).push({});
        });
        </script>
    </body>
</html>
<?php $this->endPage() ?>
