<?php
use yii\web\JsExpression;

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name');
echo $form->field($model, 'intro')->textarea();
echo $form->field($model, 'logo')->hiddenInput()->label(false);
//echo $form->field($model, 'imgFile')->fileInput();
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \xj\uploadify\Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //如果有旧图片则让他隐藏
        $('#img-old').hide();
        //如果不是隐藏则先隐藏
        if(!$('#img-logo').is(':hidden')) {
            $('#img-logo').hide();
        }
        //显示
        $('#img-logo').attr('src', data.fileUrl).fadeIn(1000);
        //隐藏域
        $('#brand-logo').val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
if ($model->logo) {
    echo \yii\bootstrap\Html::img('@web' . $model->logo, ['class' => 'img-rounded', 'style' => 'height:100px', 'id' => 'img-old']);
}

echo \yii\bootstrap\Html::img('', ['class' => 'img-rounded', 'style' => 'height:100px;display:none', 'id' => 'img-logo']);


echo $form->field($model, 'sort')->textInput(['value' => 1]);
echo $form->field($model, 'status', ['inline' => true])->radioList([1 => '正常', 0 => '隐藏']);
echo \yii\bootstrap\Html::submitButton('提交', ['class' => 'btn btn-success']);
\yii\bootstrap\ActiveForm::end();
