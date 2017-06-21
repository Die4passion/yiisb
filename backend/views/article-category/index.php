<?php
$this->title = $title;
//$this->params['breadcrumbs'][] = ['label'=>'文章列表','url'=>\yii\helpers\Url::to(['article/index'])];
$this->params['breadcrumbs'][] = $title;
?>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>类型</th>
        <?php if (Yii::$app->user->can('article-category/del')): ?>
            <th>操作</th>
        <?php endif; ?>
    </tr>
    <?php foreach ($models as $model): ?>
        <tr>
            <td><?= $model->id ?></td>
            <td><?= $model->name ?></td>
            <td><?= $model->intro ?></td>
            <td><?= $model->sort ?></td>
            <td><?= \backend\models\ArticleCategory::$statusOptions[$model->status] ?></td>
            <td><?= $model->is_help ?></td>
            <?php if (Yii::$app->user->can('article-category/del')): ?>
                <td>
                    <?= \yii\bootstrap\Html::a('编辑', ['article-category/update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
                    <?= \yii\bootstrap\Html::a('删除', ['article-category/del', 'id' => $model->id], ['class' => 'btn btn-danger btn-sm']) ?>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
</table>
<?= \yii\widgets\LinkPager::widget(['pagination' => $page]) ?>
<?php if (Yii::$app->user->can('article-category/add')): ?>
<?= \yii\bootstrap\Html::a('添加文章分类', ['article-category/add'], ['class' => 'btn btn-info']) ?>
<?php endif; ?>


