<?php

namespace backend\filters;


use yii\base\ActionFilter;
use yii\web\HttpException;

class AdminFilter extends ActionFilter
{
    //操作之前执行的代码
    public function beforeAction($action)
    {
        //判断是否是游客
        if (\Yii::$app->user->isGuest) {
            return $action->controller->redirect(\Yii::$app->user->loginUrl);
        } elseif (!\Yii::$app->user->can($action->uniqueId)) {
            //uniqueId等于当前操作路由名字
            throw new HttpException(403, '对不起，你没有权限哦~~~');
        } else {
            return parent::beforeAction($action);
        }
    }

}