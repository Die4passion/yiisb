<?php
//搜索
$this->title = $title;
//$this->params['breadcrumbs'][] = ['label'=>'','url'=>\yii\helpers\Url::to(['goods-category/index'])];
$this->params['breadcrumbs'][] = $title;
echo '<div class="container-fluid"><div class="row">';
$form = \yii\bootstrap\ActiveForm::begin([
    'method' => 'get',
    'action' => \yii\helpers\Url::to(['goods/index']),
    'options' => ['class' => 'form-inline']
]);
echo '<div class="col-lg-2">' . $form->field($search, 'name')
        ->textInput(['placeholder' => '商品名称'])
        ->label(false) . '</div>';
echo '<div class="col-lg-2">' . $form->field($search, 'sn')
        ->textInput(['placeholder' => '商品编号'])
        ->label(false) . '</div>';
echo '<div class="col-lg-2">' . $form->field($search, 'minPrice')
        ->textInput(['placeholder' => '最低价$'])
        ->label(false) . '</div> ';
echo '<div class="col-lg-2">' . $form->field($search, 'maxPrice')
        ->textInput(['placeholder' => '最高价$'])
        ->label(false) . '</div>';
echo '<div class="col-lg-2">' . \yii\bootstrap\Html::submitButton('搜索', ['class' => 'btn btn-success']) . '</div>';
\yii\bootstrap\ActiveForm::end();
echo '</div></div>';
?>
    <table class="table table-responsive table-bordered">
        <tr>
            <td>ID</td>
            <td>商品名称</td>
            <td>编号</td>
            <td>价格</td>
            <td>库存</td>
            <td>logo</td>
            <td>创建时间</td>
            <td>操作</td>
        </tr>
        <?php foreach ($models as $model): ?>
            <tr>
                <td><?= $model->id ?></td>
                <td><?= $model->name ?></td>
                <td><?= $model->sn ?></td>
                <td><?= $model->shop_price ?></td>
                <td><?= $model->stock ?></td>
                <td style="text-align: center;"><?= $model->logo ? \yii\bootstrap\Html::img($model->logo, ['class' => 'img-rounded', 'height' => '50']) : '' ?></td>
                <td><?= date('Y-m-d', $model->create_time) ?></td>
                <td>
                    <?= \yii\bootstrap\Html::a('编辑相册', ['goods/album', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']) ?>
                    <?= \yii\bootstrap\Html::a('编辑', ['goods/update', 'id' => $model->id], ['class' => 'btn btn-sm btn-warning']) ?>
                    <?= \yii\bootstrap\Html::a('删除', ['goods/del', 'id' => $model->id], ['class' => 'btn btn-sm btn-danger']) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?= \yii\widgets\LinkPager::widget([
    'pagination' => $page,
])?>
<?= \yii\bootstrap\Html::a('添加商品', ['goods/add'], ['class' => 'btn btn-info']) ?>
