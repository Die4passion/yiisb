<?php
$this->title = $title;
//$this->params['breadcrumbs'][] = ['label'=>'文章列表','url'=>\yii\helpers\Url::to(['article/index'])];
$this->params['breadcrumbs'][] = $title;
?>
<table class="table table-responsive table-bordered">
    <tr>
        <td>ID</td>
        <td>管理员名</td>
        <td>邮箱</td>
        <td>状态</td>
        <td>角色</td>
        <td>创建时间</td>
        <td>最后登录时间</td>
        <td>最后登录IP</td>
        <?php if (Yii::$app->user->can('/admin/del')):?>
        <td>操作</td>
        <?php endif;?>
    </tr>
    <?php foreach ($models as $model): ?>
        <tr id="admin_<?= $model->id ?>" data-id="<?= $model->id ?>">
            <td><?= $model->id ?></td>
            <td><?= $model->username ?></td>
            <td><?= $model->email ?></td>
            <td><?= $model->status ?></td>
            <td><?php foreach (Yii::$app->authManager->getRolesByUser($model->id) as $role) {
                    echo $role->description;
                    echo '&emsp;';
                } ?></td>
            <td><?= date('Y-m-d', $model->created_at) ?></td>
            <td><?= date('Y-m-d', $model->last_login_time) ?></td>
            <td><?= $model->last_login_ip ?></td>
            <?php if (Yii::$app->user->can('/admin/del')):?>
            <td>
                <?= \yii\bootstrap\Html::a('修改', ['admin/update', 'id' => $model->id], ['class' => 'btn btn-sm btn-warning']) ?>
                <?= \yii\bootstrap\Html::a('删除', ['admin/del', 'id' => $model->id], ['class' => 'btn btn-sm btn-danger del_btn']) ?>
                <?= \yii\bootstrap\Html::a('修改密码', ['admin/reset-password', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
            </td>
            <?php endif;?>
        </tr>
    <?php endforeach; ?>
</table>
<?= \yii\widgets\LinkPager::widget(['pagination' => $page]) ?>
<br>
<?= \yii\bootstrap\Html::a('新增管理', ['admin/add'], ['class' => 'btn btn-info']) ?>
