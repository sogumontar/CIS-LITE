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
use backend\themes\admin_lte\helpers\MenuRenderer;



/**
 * @var \yii\web\View $this
 * @var string $content
 */
AppAsset::register($this);
$this->beginPage();

?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>

        <meta charset="<?= Yii::$app->charset ?>"/>
        <title><?= Html::encode($this->title) ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <?= Html::csrfMetaTags() ?>
        <?php $this->head() ?>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        
    </head>
    <body class="skin-blue">
        <?php $this->beginBody() ?>
        <div class="body-wrapper">
        <!-- header logo: style can be found in header.less -->
            <header class="header">
                <a href="<?=Url::home() ?>" class="logo">
                    <!-- Add the class icon to your logo image or logo icon to add the margining -->
                    CIS-Del
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <div class="navbar-right">
                        <ul class="nav navbar-nav">
                            <?php if (!\Yii::$app->user->isGuest): ?>
                                <!-- Notifications: style can be found in dropdown.less -->
                                <?php
                                    $notifInfo = \Yii::$app->messenger->getMyNotificationsInfo();
                                    $notifData = \Yii::$app->messenger->getMyNotificationsData(['limit' => 10]);
                                      // \Yii::$app->debugger->print_array($notifData);
                                ?>
                                <li class="dropdown notifications-menu">
                                    <a id='notif-button' goto="<?=Url::toRoute(['/api/notification/setallseen']) ?>" href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-bell-o"></i>
                                        <?php if (isset($notifInfo->info) && $notifInfo->info->new > 0): ?>
                                            <span id='notif-banner' class="label label-danger"><?=$notifInfo->info->new ?></span>
                                        <?php endif ?>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="header">Notifications</li>
                                        <li>
                                            <ul class="menu">
                                            <?php if (isset($notifData->data)): ?>
                                                <?php foreach ($notifData->data as $data): ?>
                                                    <?php $unreadClass = (!$data->readAt)? 'info-unread':'info-read' ?>
                                                    <li class="<?=$unreadClass ?>">
                                                        <div class="notif">
                                                            <i class="ion ion-alert info"></i> <?=$data->body ?>
                                                        </div>
                                                        <div class="notif-tools">
                                                            <div notif-id="<?=$data->id ?>" data-toggle='tooltip' title='Mark as read' data-placement='left' class="notif-tools-markread" goto="<?=Url::toRoute(['/api/notification/markread', 'id'=>$data->id]) ?>">
                                                                <i class="fa fa-circle-o"></i>
                                                            </div>
                                                            <div notif-id="<?=$data->id ?>" data-toggle='tooltip' title='Delete' data-placement='left' class="notif-tools-delete" goto="<?=Url::toRoute(['/api/notification/del', 'id'=>$data->id]) ?>">
                                                                <i class="fa fa-close"></i>
                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php endforeach ?>
                                            <?php endif ?>
                                            </ul>
                                        </li>
                                        <li class="footer"><a href="#">View all</a></li>
                                    </ul>
                                </li>
                            <?php endif ?>
                            <!-- User Account: style can be found in dropdown.less -->
                            <?php
                                $userModel = null;
                                $profileName = "Guest";
                                if (!\Yii::$app->user->isGuest) {
                                    $userModel = \Yii::$app->user->getIdentity();
                                    if($userModel->profile){
                                       $profileName = $userModel->profile->first_name.' '.$userModel->profile->last_name; 
                                    }else{
                                        $profileName = $userModel->username;
                                    }
                                }
                            ?> 
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <?php if ($userModel && $userModel->profile && $userModel->profile->avatar_url): ?>
                                        <img src="<?php echo Yii::$app->fileManager->generateUri($userModel->profile->avatar_url); ?>" class="user-image" alt="User Image" />
                                    <?php else: ?>
                                        <img src="<?php echo Yii::$app->getRequest()->getHostInfo(); ?>/img/avatar3.png" class="user-image" alt="User Image" />
                                    <?php endif ?>
                                    <span>
                                    <?=$profileName ?>
                                    <i class="caret"></i></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header bg-light-blue">
                                        <?php if (!\Yii::$app->user->isGuest): ?>
                                            <?php if ($userModel && $userModel->profile && $userModel->profile->avatar_url): ?>
                                                <img src="<?php echo Yii::$app->fileManager->generateUri($userModel->profile->avatar_url); ?>" class="img-circle" alt="User Image" />
                                            <?php else: ?>
                                                <img src="<?php echo Yii::$app->getRequest()->getHostInfo(); ?>/img/avatar3.png" class="img-circle" alt="User Image" />
                                            <?php endif ?>
                                            
                                            <p>
                                                <?php 
                                                if($userModel->profile){
                                                   echo $userModel->profile->first_name.' '.$userModel->profile->last_name; 
                                                }else{
                                                    echo $userModel->username;
                                                }
                                                 ?>
                                                <small>
                                                <?php 
                                                   $wgs = \Yii::$app->privilegeControl->getWorkgroups(); 
                                                 ?>
                                                 Workgroups: 
                                                 <?php foreach ($wgs as $wg): ?>
                                                     <span class='label label-warning'><?=$wg ?></span>
                                                 <?php endforeach ?>
                                                </small>
                                            </p>
                                        <?php endif ?>
                                    </li>
        
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href=<?= Url::toRoute('/user/profile')?> class="btn btn-default btn-flat">Profile</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href=<?= Url::toRoute('/user/logout')?> class="btn btn-default btn-flat">Sign out</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <div class="wrapper row-offcanvas row-offcanvas-left">
                <!-- Left side column. contains the logo and sidebar -->
                <aside class="left-side sidebar-offcanvas">                
                    <!-- sidebar: style can be found in sidebar.less -->
                    <section class="sidebar">
                        <div class="theme-panel">
                            <div id= 'theme-option-blue' class="theme-option blue"></div>
                            <div id= 'theme-option-red' class="theme-option red"></div>
                            <div id= 'theme-option-black' class="theme-option black"></div>
                            <div id= 'theme-option-green' class="theme-option green"></div>
                        </div>
                        
                        <?php 
                            $sidebarMenu = [
                                    [
                                        'label' => 'System Administration',
                                        'icon' => 'fa-dashboard',
                                        'child' => [
                                            [
                                                'label' => 'User Management',
                                                'url' => '/admin/user/index',
                                                'icon' => 'fa-users',
                                            ],
                                        ]
                                    ],
                                    [
                                        'label' => 'Gii',
                                        'icon' => 'fa-edit',
                                        'url' => '/gii'
                                    ],
                            ];
                            //MenuFilter::renderModuleMenu($sidebarMenu);
                            $menu = MenuControl::getMenuTree('system-x-backend-sidebar', false);
                            
                            MenuRenderer::renderSidebarMenu($menu);
                        ?>
                   
                    </section>
                    <!-- /.sidebar -->
                </aside>

                <!-- Right side column. Contains the navbar and content of the page -->
                <aside class="right-side">                
                    <!-- Content Menu (Module Menu) -->
                    <section class="content-menu" id="content-menu">
                        <div class="module-menu-container">
                            <?php 

                                /**
                                 * Panggil fungsi untuk konversi array menu ke array widget menu
                                 */
                                if(isset($this->context->menuGroup)){

                                    MenuRenderer::renderContentMenu(MenuControl::getMenuTree($this->context->menuGroup, false));
                                }
                             ?>
                        </div>

                        <?php if(isset($this->params['breadcrumbs'])):
                        // print_r($this->params['breadcrumbs']);
                            echo Breadcrumbs::widget([
                                'links'                 => $this->params['breadcrumbs'],
                                'homeLink'              => ['label'=> '<i class="fa fa-home"></i> Home', 'url'=> \Yii::$app->urlManager->createUrl([''])],
                                'activeItemTemplate'    => "<li class=\"active\">{link}</li>\n",
                                'itemTemplate'          => "<li>{link}</li>\n",
                                'options'               => ['class' => 'breadcrumb'],
                                'tag'                   => 'ol',
                                'encodeLabels'          => false,
                            ]);
                        ?><!-- breadcrumbs -->
                        <?php else: ?>

                            <ol class="breadcrumb">
                                <li><a href=<?=\Yii::$app->urlManager->createUrl([''])?>><i class="fa fa-home"></i> Home</a></li>
                                <li class="active">Snap...</li>
                            </ol>
                        <?php endif; ?>   
                    </section>
                    <!-- Main content -->
                    <section class="content" id="main-content">
                        <?php 
                            if (isset($this->params['header'])) {
                        ?>
                            <div class='content-header'><h1><?=$this->params['header'] ?></h1></div>
                        <?php
                            }
                         ?>

                         <?php

                            $flashes = \Yii::$app->messenger->getInfoFlash();
                            $msgConcat = '';
                            if($flashes){
                                $msgConcat = $flashes[0];
                                for($i=1;$i<count($flashes);$i++){
                                    $msgConcat .= "<hr/>".$flashes[$i];
                                } 
                        ?>
                            <div class="alert alert-info alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4>    <i class="icon fa fa-info"></i> Info!</h4>
                                    <?=$msgConcat ?>
                            </div>
                        <?php } ?>

                        <?php

                            $flashes = \Yii::$app->messenger->getSuccessFlash();

                            if($flashes){
                                $msgConcat = $flashes[0];
                                for($i=1;$i<count($flashes);$i++){
                                    $msgConcat .= "<hr/>".$flashes[$i];
                                } 
                        ?>
                            <div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4>    <i class="icon fa fa-check"></i> Success!</h4>
                                    <?=$msgConcat ?>
                            </div>
                        <?php } ?>

                        <?php

                            $flashes = \Yii::$app->messenger->getWarningFlash();

                            if($flashes){
                                $msgConcat = $flashes[0];
                                for($i=1;$i<count($flashes);$i++){
                                    $msgConcat .= "<hr/>".$flashes[$i];
                                } 
                        ?>
                            <div class="alert alert-warning alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4>    <i class="icon fa fa-warning"></i> Warning!</h4>
                                    <?=$msgConcat ?>
                            </div>
                        <?php } ?>

                        <?php

                            $flashes = \Yii::$app->messenger->getErrorFlash();

                            if($flashes){
                                $msgConcat = $flashes[0];
                                for($i=1;$i<count($flashes);$i++){
                                    $msgConcat .= "<hr/>".$flashes[$i];
                                } 
                        ?>
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4>    <i class="icon fa fa-ban"></i> Error!</h4>
                                    <?=$msgConcat ?>
                            </div>
                        <?php } ?>


                        <?= $content ?>

                    </section><!-- /.content -->
                </aside><!-- /.right-side -->
            </div><!-- ./wrapper -->

            </div>
            <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>