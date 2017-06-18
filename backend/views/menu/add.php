<?php
$this->title = $title;
$this->params['breadcrumbs'][] = ['label'=>'菜单列表','url'=>\yii\helpers\Url::to(['menu/index'])];
$this->params['breadcrumbs'][] = $title;
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'parent_id')->dropDownList(\backend\models\Menu::getParents());
echo $form->field($model, 'label');
echo $form->field($model, 'url')->dropDownList(\backend\models\RoleForm::getPermissionOptions(), ['prompt' => '>>>请选择url<<<']);
echo $form->field($model, 'description')->textarea();
echo $form->field($model, 'sort')->textInput(['value' => 1]);
echo \yii\bootstrap\Html::submitButton('提交', ['class' => 'btn btn-success']);
\yii\bootstrap\ActiveForm::end();