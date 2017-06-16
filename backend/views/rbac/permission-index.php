<table class="table table-bordered table-hover">
    <tr>
        <th>权限</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model): ?>
    <tr>
        <td><?= $model->name ?></td>
        <td><?= $model->description ?></td>
        <td>
            <?= \yii\bootstrap\Html::a('修改', ['rbac/update-permission', 'name' => $model->name], ['class' => 'btn btn-warning btn-sm']) ?>
            <?= \yii\bootstrap\Html::a('删除', ['rbac/del-permission', 'name' => $model->name], ['class' => 'btn btn-danger btn-sm']) ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?= \yii\bootstrap\Html::a('添加权限', ['rbac/add-permission'], ['class' => 'btn btn-info btn-sm']) ?>
