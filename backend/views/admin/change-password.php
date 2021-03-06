<?php
$this->title = $title;
//$this->params['breadcrumbs'][] = ['label'=>'文章列表','url'=>\yii\helpers\Url::to(['article/index'])];
$this->params['breadcrumbs'][] = $title;

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'username')->textInput(['readonly'=>true,'value'=>$admin->username]);
echo $form->field($model, 'old_password')->passwordInput();
echo $form->field($model, 'new_password')->passwordInput();
echo $form->field($model, 're_password')->passwordInput();
echo $form->field($model, 'code')->widget(\yii\captcha\Captcha::className(), [
    'template' => '<div class="row"><div class="col-lg-2">{input}</div><div class="col-lg-1">{image}</div></div>',
]);
echo \yii\bootstrap\Html::submitInput('确认修改', ['class' => 'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();