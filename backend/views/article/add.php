<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name');
echo $form->field($model, 'intro');
echo $form->field($detail, 'content')->textarea(['style' => 'resize:none;height:100px']);
echo $form->field($model, 'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map($cates, 'id', 'name'));
echo $form->field($model, 'sort')->textInput(['value' => 1]);
echo $form->field($model, 'status', ['inline' => true])->radioList([1 => '饱满', 0 => '萎靡']);
echo \yii\bootstrap\Html::submitButton('提交', ['class' => 'btn btn-success']);
\yii\bootstrap\ActiveForm::end();
