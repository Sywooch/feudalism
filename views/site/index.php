<?php

use yii\authclient\widgets\AuthChoice;

/* @var $this yii\web\View */
$this->title = 'Feudalism';

?>
<h1><big>Feudalism</big> <small><em>alpha</em></small></h1>
<?=AuthChoice::widget(['baseAuthUrl' => ['site/auth']])?>