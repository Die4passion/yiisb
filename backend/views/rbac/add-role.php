<?php
$this->title = $title;
$this->params['breadcrumbs'][] = ['label'=>'角色列表','url'=>\yii\helpers\Url::to(['rbac/role-index'])];
$this->params['breadcrumbs'][] = $title;
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name');
echo $form->field($model, 'description')->textarea();
echo $form->field($model, 'permissions', ['inline' => true])->checkboxList(\backend\models\RoleForm::getPermissionOptions());
echo \yii\bootstrap\Html::submitButton('提交', ['class' => 'btn btn-info']);
\yii\bootstrap\ActiveForm::end();