<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name');
echo $form->field($model, 'intro')->textarea();
echo $form->field($model, 'imgFile')->fileInput();
echo $model->logo ? \yii\bootstrap\Html::img($model->logo, ['class' => 'img-rounded', 'style' => 'height:100px']) : '';
echo $form->field($model, 'sort')->textInput(['value' => 1]);
echo $form->field($model, 'status', ['inline' => true])->radioList([1=>'正常',0=>'隐藏']);
echo \yii\bootstrap\Html::submitButton('提交', ['class' => 'btn btn-success']);
\yii\bootstrap\ActiveForm::end();
