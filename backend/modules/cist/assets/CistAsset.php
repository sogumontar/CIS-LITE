<?php
namespace backend\modules\cist\assets;

use yii\web\AssetBundle;

class CistAsset extends AssetBundle{
    public $sourcePath = '@backend/modules/cist/assets/web';

    public $css = [
        'plugins/daterangepicker/daterangepicker.css',
        'plugins/datepicker/datepicker3.css',
        'plugins/iCheck/all.css',
        'plugins/colorpicker/bootstrap-colorpicker.min.css',
        'plugins/timepicker/bootstrap-timepicker.min.css',
        'plugins/select2/select2.min.css',
        // 'dist/css/AdminLTE.min.css',
        'dist/css/skins/_all-skins.min.css',
        'dist/css/step.css',
        'jquery_ui/themes/pepper-grinder/jquery-ui.css',
        'mdp/jquery-ui.multidatespicker.css',
    ];

    public $js = [
        // 'plugins/jQuery/jquery-2.2.3.min.js',
        'plugins/select2/select2.full.min.js',
        'plugins/input-mask/jquery.inputmask.js',
        'plugins/input-mask/jquery.inputmask.date.extensions.js',
        'plugins/input-mask/jquery.inputmask.extensions.js',
        'plugins/daterangepicker/moment.js',
        'plugins/daterangepicker/daterangepicker.js',
        'plugins/datepicker/bootstrap-datepicker.js',
        'plugins/colorpicker/bootstrap-colorpicker.min.js',
        'plugins/timepicker/bootstrap-timepicker.min.js',
        // 'plugins/slimScroll/jquery.slimscroll.min.js',
        // 'plugins/iCheck/icheck.min.js',
        // 'plugins/fastclick/fastclick.js',
        'dist/js/app.min.js',
        'dist/js/demo.js',
        'jquery_ui/jquery-ui.min.js',
        //'mdp/jquery-ui.multidatespicker.js',
        'js/pageScript.js',
        'js/multidatePicker.js',
        'js/otherScript.js',
        'js/otherScript2.js',
        'js/dateDif.js',
    ];

    public $depends = [
        'backend\themes\v2\assets\V2Asset'
    ];

    public $publishOptions = [
        'forceCopy' => false,
    ];

}
