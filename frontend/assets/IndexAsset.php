<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Index frontend application asset bundle.
 */
class IndexAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/footer.css',
        'style/index.css',
        'style/bottomnav.css',
    ];
    public $js = [
        'js/header.js',
        'js/index.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}

