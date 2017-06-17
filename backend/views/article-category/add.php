<?php
$this->title = $title;
$this->params['breadcrumbs'][] = ['label'=>'文章分类列表','url'=>\yii\helpers\Url::to(['article-category/index'])];
$this->params['breadcrumbs'][] = $title;
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name');
echo $form->field($model, 'intro')->textarea();
echo $form->field($model, 'sort')->textInput(['value' => 1]);
echo $form->field($model, 'status', ['inline' => true])->radioList([1 => '饱满', 0 => '萎靡']);
echo $form->field($model, 'is_help')->textInput(['value' => 1]);
echo \yii\bootstrap\Html::submitButton('提交', ['class' => 'btn btn-success']);
\yii\bootstrap\ActiveForm::end();
