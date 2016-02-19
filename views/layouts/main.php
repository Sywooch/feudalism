<?php

use yii\helpers\Html,
    app\assets\AppAsset;

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
        <?=Html::img('/img/ajax-loader.gif', ['id'=>'spinner','style'=>'display:none','alt'=>'Загрузка'])?>
        <div>
            <?= $content ?>
        </div>
        <footer class='footer'>
            <p><?=Yii::t('app','Developed by')?> <?=Html::a('LazzyTeam', 'http://lazzyteam.pw', ['target'=>'_blank'])?></p>
            <p><?=Yii::t('app','Icons by')?> <?=Html::a('RainDropMemory', 'http://raindropmemory.deviantart.com/', ['target'=>'_blank'])?></p>
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
