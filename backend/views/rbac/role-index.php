<table class="table table-bordered table-hover">
    <tr>
        <th>角色名</th>
        <th>描述</th>
        <th>权限</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model): ?>
        <tr>
            <td><?= $model->name ?></td>
            <td><?= $model->description ?></td>
            <td><?php foreach (Yii::$app->authManager->getPermissionsByRole($model->name) as $permission) {
                echo $permission->description;
                echo '&emsp;';
                } ?></td>
            <td>
                <?= \yii\bootstrap\Html::a('修改', ['rbac/update-role', 'name' => $model->name], ['class' => 'btn btn-warning btn-sm']) ?>
                <?= \yii\bootstrap\Html::a('删除', ['rbac/del-role', 'name' => $model->name], ['class' => 'btn btn-danger btn-sm']) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?= \yii\bootstrap\Html::a('添加角色', ['rbac/add-role'], ['class' => 'btn btn-info btn-sm']) ?>
