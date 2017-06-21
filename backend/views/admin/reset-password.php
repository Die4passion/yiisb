<?php
$this->title = $title;
$this->params['breadcrumbs'][] = ['label'=>'管理员列表','url'=>\yii\helpers\Url::to(['admin/index'])];
$this->params['breadcrumbs'][] = $title;
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'username')->textInput(['readonly' => true, 'value'=>$model->username]);
echo $form->field($model, 'password');
echo \yii\bootstrap\Html::submitButton($title, ['class' => 'btn btn-info']);
\yii\bootstrap\ActiveForm::end();