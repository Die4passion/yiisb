<?php
use xj\uploadify\Uploadify;
use yii\bootstrap\Html;
use yii\web\JsExpression;
$this->title = $title;
$this->params['breadcrumbs'][] = ['label'=>'商品列表','url'=>\yii\helpers\Url::to(['goods/index'])];
$this->params['breadcrumbs'][] = $title;
echo Html::fileInput('test', NULL, ['id' => 'test']);
echo Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData' => ['goods_id' => $goods->id],
        'width' => 120,
        'height' => 40,
        'onUploadError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('文件 ' + file.name + ' 无法被上传: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        //console.log(data);
        //$("#brand-logo").val(data.fileUrl);
        //$("#img").attr("src",data.fileUrl);
        var html='<tr data-id="'+data.goods_id+'" id="album_'+data.goods_id+'">';
        html += '<td><img src="'+data.fileUrl+'" /></td>';
        html += '<td><button type="button" class="btn btn-danger del_btn">删除</button></td>';
        html += '</tr>';
        $("table").append(html);
    }
}
EOF
        ),
    ]
]);
?>
    <table class="table table-bordered">
        <tr>
            <th>图片</th>
            <th>操作</th>
        </tr>
        <?php foreach ($goods->albums as $album): ?>
            <tr id="album_<?= $album->id ?>" data-id="<?= $album->id ?>">
                <td><?= Html::img($album->img_path) ?></td>
                <td><?= Html::button('删除', ['class' => 'btn btn-danger del_btn']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php
$url = \yii\helpers\Url::to(['del-album']);
$this->registerJs(new JsExpression(
    <<<EOT
    $("table").on('click',".del_btn",function(){
        if(confirm("确定删除该图片吗?")){
        var id = $(this).closest("tr").attr("data-id");
            $.post("{$url}",{id:id},function(data){
                if(data=="success"){
                    //alert("删除成功");
                    $("#album_"+id).remove();
                }
            });
        }
    });
EOT

));
echo \yii\bootstrap\Html::a('返回列表','index',['class'=> 'btn btn-info']);