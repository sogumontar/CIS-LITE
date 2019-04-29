<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\ButtonDropdown;  
use yii\bootstrap\Dropdown; 
use yii\widgets\Breadcrumbs;
use yii\helpers\BaseUrl;
use yii\helpers\Url;
use common\components\MenuControl;
use backend\themes\v2\assets\V2Asset;
use backend\themes\v2\helpers\MenuRenderer;



/**
 * @var \yii\web\View $this
 * @var string $content
 */
AppAsset::register($this);
V2Asset::register($this);
$this->beginPage();

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <title><?= Html::encode($this->title) ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
    

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition login-page">
    <?php $this->beginBody() ?>
    <?= $content ?>
    <?php $this->endBody() ?>
  </body>
</html>
<?php $this->endPage() ?>
