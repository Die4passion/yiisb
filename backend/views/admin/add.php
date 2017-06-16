<h1><?= $title ?></h1>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'username');
echo $model->isNewRecord ? $form->field($model, 'password')->passwordInput() : '';
echo $form->field($model, 'email');
echo $form->field($model, 'status');
echo $form->field($roles, 'userRoles', ['inline' => true])->checkboxList(\backend\models\RoleForm::getRolesOptions());
echo \yii\bootstrap\Html::submitButton($title, ['class' => 'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
