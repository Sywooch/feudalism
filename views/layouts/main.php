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
        <link rel="icon" sizes="512x512" href="/img/logo512.png">
        <meta name="theme-color" content="#3A2658">
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
            <?php if (!Yii::$app->user->isGuest):?>
            <p>
                <?=Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    Yii::t('app','Logout ({0})', [Yii::$app->user->identity->name]),
                    ['class' => 'btn btn-link']
                )
                . Html::endForm()?>
            </p>
            <?php endif ?>
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
