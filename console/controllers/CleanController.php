<?php

namespace console\controllers;


use backend\models\Goods;
use frontend\models\Order;
use yii\console\Controller;

class CleanController extends Controller
{
    //清理超时订单
    public function actionOrder()
    {
        //不限制脚本执行时间
        set_time_limit(0);
        //死循环
        while (1) {
            $models = Order::find()
                ->where(['status'=>1])
                ->andWhere(['<', 'create_time', time()-30])
                ->all();
            foreach ($models as $model) {
                //状态改为已取消
                $model->status = 0;
                $model->save();
                //返还库存
                foreach ($model->goods as $goods) {
                    Goods::updateAllCounters(['stock'=>$goods->amount],'id='.$goods->goods_id);
                }
                echo "order ID = ".$model->id." has been cleaned\n";
            }
            //每秒钟执行一次;
            sleep(1);
        }
    }
}