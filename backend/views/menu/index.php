<?php
$this->title = $title;
//$this->params['breadcrumbs'][] = ['label'=>'文章列表','url'=>\yii\helpers\Url::to(['article/index'])];
$this->params['breadcrumbs'][] = $title;
?>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>菜单名</th>
        <th>上级菜单名</th>
        <th>排序</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?= $model->id ?></td>
            <td><?= $model->label ?></td>
            <td><?= $model->parent_id ? $model->parent->label : '顶级菜单' ?></td>
            <td><?= $model->sort ?></td>
            <td><?= $model->description ?></td>
            <td>
                <?= \yii\bootstrap\Html::a('编辑', ['menu/update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
                <?= \yii\bootstrap\Html::a('删除', ['menu/del', 'id' => $model->id], ['class' => 'btn btn-danger btn-sm']) ?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<div class="container">
    <div class="row">
        <div class="col-lg-2" style="margin-top: 25px;">
            <?= \yii\bootstrap\Html::a('添加菜单', ['menu/add'], ['class' => 'btn btn-info']) ?>

        </div>
        <div class="col-lg-4 col-lg-offset-6" >

            <?= \yii\widgets\LinkPager::widget(['pagination' => $page]) ?>
        </div>

    </div>
</div>