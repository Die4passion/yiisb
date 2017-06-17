<?php
$this->title = $title;
//$this->params['breadcrumbs'][] = ['label'=>'商品分类首页','url'=>\yii\helpers\Url::to(['goods-category/index'])];
$this->params['breadcrumbs'][] = $title;
?>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>分类</th>
        <th>排序</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?= $model->id ?></td>
        <td><?= $model->name ?></td>
        <td><?= $model->intro ?></td>
        <td><?= $model->articleCategory->name ?></td>
        <td><?= $model->sort ?></td>
        <td><?= \backend\models\Article::$statusOptions[$model->status] ?></td>
        <td><?= date('Y-m-d',$model->create_time) ?></td>
        <td>
            <?= \yii\bootstrap\Html::a('查看', ['article/content', 'id' => $model->id], ['class' => 'btn btn-success btn-sm']) ?>
            <?= \yii\bootstrap\Html::a('编辑', ['article/update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
            <?= \yii\bootstrap\Html::a('删除', ['article/del', 'id' => $model->id], ['class' => 'btn btn-danger btn-sm']) ?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?= \yii\widgets\LinkPager::widget(['pagination' => $page]) ?>
<?= \yii\bootstrap\Html::a('添加文章', ['article/add'], ['class' => 'btn btn-info']) ?>
