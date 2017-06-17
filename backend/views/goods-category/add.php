<?php
/**
 * @var $this \Yii\web\View
 */
$this->title = $title;
$this->params['breadcrumbs'][] = ['label'=>'商品分类首页','url'=>\yii\helpers\Url::to(['goods-category/index'])];
$this->params['breadcrumbs'][] = $title;
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name');
echo $form->field($model, 'parent_id')->hiddenInput();
//echo $form->field($model, 'parent_id')->dropDownList($options);
echo '<ul id="treeDemo" class="ztree"></ul>';
echo $form->field($model, 'intro')->textarea(['style' => 'resize:none;height:100px']);
echo \yii\bootstrap\Html::submitButton('提交', ['class' => 'btn btn-info']);
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
              $("#goodscategory-parent_id").val(treeNode.id);
            }
        }
    };
    //zTree的数据属性
    var zNodes = {$zNodes};
    zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    //展开所有节点
    zTreeObj.expandAll(true);
    //获取当前节点的父节点
    var node = zTreeObj.getNodeByParam("id", $("#goodscategory-parent_id").val(), null);
    //选中当前节点的父节点
    zTreeObj.selectNode(node);
    //打开这个节点
    // zTreeObj.expand();
JS

);
$this->registerJs($js);
