<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '新时代的我们',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => '商品分类','url' => ['/goods-category/index']],
        ['label' => '品牌', 'url' => ['/brand/index']],
        ['label' => '商品', 'url' => ['/goods/index']],
        ['label' => '文章分类', 'url' => ['/article-category/index']],
        ['label' => '文章', 'url' => ['/article/index']],
        ['label' => '管理员', 'url' => ['/admin/index']],
        ['label' => 'RBAC', 'items' =>[
            ['label' => '权限列表',  'url' =>['/rbac/permission-index']],
            ['label' => '角色列表',  'url' =>['/rbac/role-index']],
        ]]
    ];
    if (Yii::$app->user->isGuest) {
//        $menuItems[] = ['label' => '注册', 'url' => ['/admin/signup']];
        $menuItems[] = ['label' => '登录', 'url' => ['/admin/login']];
    } else {
        //获取当前登录用户对应的菜单
        $menuItems = \yii\helpers\ArrayHelper::merge($menuItems, Yii::$app->user->identity->getMenuItems());
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                '注销 (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; 达盖尔的旗帜 <?= date('Y-m-d') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
