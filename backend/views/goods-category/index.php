<table class="cate table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <!--        <th>上级分类</th>-->
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model): ?>
        <tr data-lft="<?= $model->lft ?>" data-rgt="<?= $model->rgt ?>" data-tree="<?= $model->tree ?>"
            data-depth="<?= $model->depth ?>">
            <td><?= $model->id ?></td>
            <td><?= str_repeat('▬▬ ', $model->depth) . $model->name ?>
                <?= $model->isLeaf() ? '' : '<span class="toggle_cate glyphicon glyphicon-chevron-up" style="float: right">
                </span>' ?>
            </td>
            <td><?= $model->intro ?></td>
            <td>
                <?= \yii\bootstrap\Html::a('编辑', ['goods-category/update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
                <?= \yii\bootstrap\Html::a('删除', ['goods-category/del', 'id' => $model->id], ['class' => 'btn btn-danger btn-sm']) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?= \yii\bootstrap\Html::a('添加商品分类', ['goods-category/add'], ['class' => 'btn btn-info']) ?>
<?php
$js = <<<JS
$(".toggle_cate").on("click",function () {
        //查找当前分类的子孙分类的子孙（根据 tree lft rgt）
        var tr = $(this).closest('tr');
        var tree = tr.attr('data-tree') - 0;
        var lft = tr.attr('data-lft') - 0;
        var rgt = tr.attr('data-rgt') - 0;
        //获得层级
        var depth = tr.attr('data-depth') - 0;
        //判断状态
        var show = $(this).hasClass('glyphicon-chevron-up');
        $(this).toggleClass('glyphicon-chevron-down');
        $(this).toggleClass('glyphicon-chevron-up');
        $(".cate tr").each(function() {
            //同一颗树 左值大于lft 右值小于rgt 只显示下一级分类
            if($(this).attr('data-tree') == tree && $(this).attr('data-lft') > lft && $(this).attr('data-rgt') < rgt && $(this).attr('data-depth') == depth + 1) {
                //如果是展开的则让他收起
                if($(this).find('.toggle_cate').hasClass('glyphicon-chevron-down')) {
                    $(this).find('.toggle_cate').trigger('click');
                }
                //全部显示下级分类
                show ? $(this).fadeIn(666) : $(this).fadeOut(666);
            }
        });
    });
$(function(){
    $(".cate tr:not(:first)").each(function() {
       if($(this).attr('data-depth') != 0){
           $(this).hide();
       }
    });
});
JS;
$this->registerJs($js);