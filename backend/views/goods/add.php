<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name');
echo $form->field($model, 'logo')->hiddenInput();
//外部TAG
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \xj\uploadify\Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new \yii\web\JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('文件 ' + file.name + ' 无法上传: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new \yii\web\JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //如果有旧图片则让他隐藏
        $('#img-old').hide();
        //如果不是隐藏则先隐藏
        if(!$('#img-logo').is(':hidden')) {
            $('#img-logo').hide();
        }
        //显示
        $('#img-logo').attr('src', data.fileUrl).fadeIn(1000);
        //隐藏域
        $('#goods-logo').val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
if ($model->logo) {
    echo \yii\bootstrap\Html::img('@web' . $model->logo, ['class' => 'img-rounded', 'style' => 'height:100px', 'id' => 'img-old']);
}

echo \yii\bootstrap\Html::img('', ['class' => 'img-rounded', 'style' => 'height:100px;display:none', 'id' => 'img-logo']);

echo $form->field($model, 'goods_category_id')->hiddenInput();
echo '<ul id="treeDemo" class="ztree"></ul>';
echo $form->field($model, 'brand_id')->dropDownList(\backend\models\Goods::getBrands(), ['prompt' => '>>请选择分类<<']);
echo $form->field($model, 'market_price');
echo $form->field($model, 'shop_price');
echo $form->field($model, 'stock');
echo $form->field($model, 'is_on_sale', ['inline' => true])->radioList(\backend\models\Goods::$saleOptions);
echo $form->field($model, 'status', ['inline' => true])->radioList(\backend\models\Goods::$statusOptions);
echo $form->field($model, 'sort');
echo $form->field($intro, 'content')->widget('kucha\ueditor\UEditor', [
    'clientOptions' => [
        //编辑区域大小
        'initialFrameHeight' => '200',
        //设置语言
        'lang' => 'zh-cn', //中文为 zh-cn
//        //定制菜单
//        'toolbars' => [
//            [
//                'fullscreen', 'source', 'undo', 'redo', '|',
//                'fontsize',
//                'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat',
//                'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
//                'forecolor', 'backcolor', '|',
//                'lineheight', '|',
//                'indent', '|'
//            ],
//        ]
    ]
]);
echo \yii\bootstrap\Html::submitButton('提交', ['class' => 'btn btn-success']);
\yii\bootstrap\ActiveForm::end();
//使用ztree，加载静态资源
$this->registerCssFile('@web/zTree/css/metroStyle/metroStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js', ['depends' => \yii\web\JqueryAsset::className()]);
$zNodes = \yii\helpers\Json::encode($categories);
$js = new \yii\web\JsExpression(
    <<<JS
     var zTreeObj; 
    //zTree的配置参数
    var setting = {
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "parent_id",
                rootPid: 0
            }
        },
        callback: {
            onClick: function(event, treeId, treeNode) {
              //console.log(treeNode.id);
              //将id赋值给input
              $("#goods-goods_category_id").val(treeNode.id);
            }
        }
    };
    //zTree的数据属性
    var zNodes = {$zNodes};
    zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    //展开所有节点
    zTreeObj.expandAll(true);
    //获取当前节点的父节点
    var node = zTreeObj.getNodeByParam("id", $("#goods-goods_category_id").val(), null);
    //选中当前节点的父节点
    zTreeObj.selectNode(node);
    //打开这个节点
    // zTreeObj.expand();
JS

);
$this->registerJs($js);