<?php
$this->title = $title;
//$this->params['breadcrumbs'][] = ['label'=>'d','url'=>\yii\helpers\Url::to(['goods-category/index'])];
$this->params['breadcrumbs'][] = $title;
?>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>品牌名</th>
        <th>简介</th>
        <th>LOGO</th>
        <th>排序</th>
        <th>状态</th>
        <?php if (Yii::$app->user->can('brand/del')): ?>
            <th>操作</th>
        <?php endif; ?>
    </tr>
    <?php foreach ($models as $model): ?>
        <tr>
            <td><?= $model->id ?></td>
            <td><?= $model->name ?></td>
            <td><?= $model->intro ?></td>
            <td style="text-align: center;"><?= $model->logo ? \yii\bootstrap\Html::img($model->logo, ['class' => 'img-rounded', 'height' => '50']) : '' ?></td>
            <td><?= $model->sort ?></td>
            <td><?= \backend\models\Brand::$statusOptions[$model->status] ?></td>
            <?php if (Yii::$app->user->can('brand/del')): ?>
                <td>
                    <?= \yii\bootstrap\Html::a('编辑', ['brand/update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
                    <?= \yii\bootstrap\Html::a('删除', ['brand/del', 'id' => $model->id], ['class' => 'btn btn-danger btn-sm']) ?>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach ?>
</table>
<?= \yii\widgets\LinkPager::widget(['pagination' => $page]) ?>
<br>
<?php if (Yii::$app->user->can('brand/add')): ?>
<?= \yii\bootstrap\Html::a('添加品牌', ['brand/add'], ['class' => 'btn btn-info']) ?>
<?php endif; ?>
