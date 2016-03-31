<?php

use yii\helpers\Html,
    app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

$this->registerJs('init()');

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
        <link rel="icon" sizes="32x32" href="/img/logo32.png">
        <meta name="theme-color" content="#222222">
    </head>
    <body>
        <?php $this->beginBody() ?>
        <?=Html::img('/img/ajax-loader.gif', ['id'=>'spinner','style'=>'display:none','alt'=>'Загрузка'])?>
        <div id="wrapper">
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only"><?=Yii::t('app', 'Toggle navigation')?></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/">Ω <?=Yii::t('app', 'Feudalism')?></a>
                </div>
                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <?php if (!Yii::$app->user->isGuest):?>
                    <ul class="nav navbar-nav navbar-right navbar-user">
                        <li class="dropdown user-dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?=Yii::$app->user->identity->fullName?> <span class="badge">2</span> <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="/user/view?id=<?=Yii::$app->user->id?>"><i class="fa fa-user"></i> Profile</a></li>
                                <li><a href="#"><i class="fa fa-gear"></i> <?=Yii::t('app', 'Settings')?></a></li>
                                <li class="divider"></li>
                                <li class="dropdown-header"><?=Yii::t('app', 'Messages')?></li>
                                <li><a href="#"><?=Yii::t('app', 'Go to Inbox')?> <span class="badge">2</span></a></li>
                                <li class="divider"></li>
                                <li>
                                    <?=Html::beginForm(['/site/logout'], 'post')
                                        . Html::submitButton(
                                            Yii::t('app','Logout ({0})', [Yii::$app->user->identity->name]),
                                            ['class' => 'btn btn-link']
                                        )
                                        . Html::endForm()?>
                                </li>

                            </ul>
                        </li>
                    </ul>
                    <?php endif ?>
                </div>
          </nav>
            <div id="page-wrapper">
                <?= $content ?>
            </div>
        </div>
        <footer id="footer" class="footer">
            <div class="container">
                <div class="col-md-3 col-xs-6 col-sm-6 text-left copyright">
                    <?=Yii::t('app','Developed by')?> <?=Html::a('LazzyTeam', 'http://lazzyteam.pw', ['target'=>'_blank'])?>
                </div>
                <div class="col-md-3 col-xs-6 col-sm-6 text-right col-md-offset-6">
                    <?=Html::a(Yii::t('app', 'About'), '/about')?>
                    |
                    <?=Html::a(Yii::t('app', 'Help'), 'http://wiki.feudalism.pw', ['target'=>'_blank'])?>
                    |
                    <?=Html::a(Yii::t('app', 'Blog'), 'http://blog.feudalism.pw', ['target'=>'_blank'])?>
                </div>
            </div>
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
