<?php
/*
 * @param
 */
$this->title = $title;
//$this->params['breadcrumbs'][] = ['label'=>'文章列表','url'=>\yii\helpers\Url::to(['article/index'])];
$this->params['breadcrumbs'][] = $title;
?>

    <table id="example" class="display" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>权限</th>
            <th>描述</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
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
        </tbody>
        <tfoot>
        <tr>
            <th>权限</th>
            <th>描述</th>
            <th>操作</th>
        </tr>
        </tfoot>
    </table>
<?= \yii\bootstrap\Html::a('添加权限', ['rbac/add-permission'], ['class' => 'btn btn-info btn-sm']) ?>
<?php
$this->registerCssFile('@web/css/jquery.dataTables.min.css');
$this->registerJsFile('@web/js/jquery.dataTables.min.js');
$js=<<<JS
$(document).ready(function() {
    $('#example').DataTable();
} );
JS;
$this->registerJs($js);
