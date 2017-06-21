<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Address frontend application asset bundle.
 */
class AddressAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/footer.css',
        'style/bottomnav.css',
        'style/home.css',
        'style/address.css',
    ];
    public $js = [
        'js/header.js',
        'js/index.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
}

