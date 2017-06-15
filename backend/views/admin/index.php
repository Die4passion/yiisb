<h1>管理列表</h1>
<table class="table table-responsive table-bordered">
    <tr>
        <td>ID</td>
        <td>管理员名</td>
        <td>邮箱</td>
        <td>状态</td>
        <td>创建时间</td>
        <td>最后登录时间</td>
        <td>最后登录IP</td>
        <td>操作</td>
    </tr>
    <?php foreach ($models as $model): ?>
        <tr id="admin_<?= $model->id ?>" data-id="<?= $model->id ?>">
            <td><?= $model->id ?></td>
            <td><?= $model->username ?></td>
            <td><?= $model->email ?></td>
            <td><?= $model->status ?></td>
            <td><?= date('Y-m-d', $model->created_at) ?></td>
            <td><?= date('Y-m-d', $model->last_login_time) ?></td>
            <td><?=  $model->last_login_ip ?></td>
            <td>
                <?= \yii\bootstrap\Html::a('修改密码', ['admin/reset-password', 'id' => $model->id], ['class' => 'btn btn-success btn-sm']) ?>
                <?= \yii\bootstrap\Html::a('修改', ['admin/update', 'id' => $model->id], ['class' => 'btn btn-sm btn-warning']) ?>
                <?= \yii\bootstrap\Html::a('删除', ['admin/del', 'id' => $model->id], ['class' => 'btn btn-sm btn-danger del_btn']) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?= \yii\bootstrap\Html::a('新增管理', ['admin/add'], ['class' => 'btn btn-info']) ?>
