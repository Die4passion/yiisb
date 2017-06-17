<?php
$this->title = $title;
$this->params['breadcrumbs'][] = ['label'=>'权限列表','url'=>\yii\helpers\Url::to(['rbac/permission-index'])];
$this->params['breadcrumbs'][] = $title;
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'description')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
