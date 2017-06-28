<?php

namespace frontend\controllers;


use frontend\models\NumForm;
use yii\web\Controller;

class TestController extends Controller
{
    //数字游戏
    public function actionNum($begin=false,$num='')
    {
        $this->layout = 'main';
        //接收数据
        $model = new NumForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //随机数
            $num = base64_decode($num);
            //用户输入的数
            $input = $model->input;
            //转换成数组
            $num_arr = str_split($num);
            $input_arr = str_split($input);
            //遍历两个数组比较
            $s1 = 0;
            $s2 = 0;
            foreach ($num_arr as $k=>$v) {
                foreach ($input_arr as $k2=>$v2) {
                        //位置和值都相等
                        if ($v == $v2 && $k == $k2) {
                            $s1++;
                            //值相等，位置不相等
                        } elseif ($v == $v2) {
                            $s2++;
                        }
                    }
                }
            $result = $s1.'A'.$s2.'B';
        } else {
            $result = null;
        }
        return $this->render('num',['model'=>$model,'begin'=>$begin,'num'=>$num,'result'=>$result]);
    }
    //开始新游戏按钮
    public function actionBegin()
    {
        //将num转换为字符串
        $num = sprintf("%04d", rand(0000,9999));
        $begin = true;
        //回到页面
        return $this->actionNum($begin,$num);
    }
}