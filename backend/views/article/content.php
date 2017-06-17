<?php
$this->title = $title;
$this->params['breadcrumbs'][] = ['label'=>'文章列表','url'=>\yii\helpers\Url::to(['article/index'])];
$this->params['breadcrumbs'][] = $title;
?>
<div class="text-center">
    <h1><?= $model->name ?></h1>
    <h5>发表时间: <?= $model->create_time ?></h5>
</div>
<p><?= $detail->content ?></p>


