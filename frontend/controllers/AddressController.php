<?php

namespace frontend\controllers;


use frontend\models\Address;
use frontend\models\Locations;
use yii\web\Controller;

class AddressController extends Controller
{
    public $layout = 'address';

    //收货地址首页
    public function actionIndex()
    {
        $model = new Address();
        $addresses = Address::findAll(['member_id' => \Yii::$app->user->identity->getId()]);
        if ($model->load(\Yii::$app->request->post())) {
            if ($model->is_default){
                self::cancelDefault();
            }
            $model->save(false);
            return $this->redirect(['address/index']);
        } else {
            return $this->render('address', ['model' => $model, 'addresses' => $addresses]);
        }
    }

    //修改收货地址
    public function actionUpdate($id)
    {
        $model = Address::findOne(['id' => $id]);
        $addresses = Address::findAll(['member_id' => \Yii::$app->user->identity->getId()]);
        if ($model->load(\Yii::$app->request->post())) {
            if ($model->is_default){
                self::cancelDefault();
            }
            $model->save(false);
            return $this->redirect(['address/index']);
        } else {
            return $this->render('address', ['model' => $model, 'addresses' => $addresses]);
        }
    }

    //删除收货地址
    public function actionDel($id)
    {
        Address::deleteAll(['id' => $id]);
        return $this->redirect(['address/index']);
    }

    //所在地区
    public function actionLocations()
    {
        if (\Yii::$app->request->isAjax) {
            $id = \Yii::$app->request->get('pid');
            $locations = Locations::find()->asArray()->where(['parent_id' => $id])->all();
//        var_dump($locations);exit;
            $response = \Yii::$app->response;
            $response->format = \yii\web\Response::FORMAT_JSON;
            $response->data = $locations;
            return $response->send();
        } else {
            return false;
        }
    }

    //设置默认地址
    public function actionDefault($id)
    {
        self::cancelDefault();
        $model = Address::findOne(['id' => $id]);
        $model->is_default = 1;
        $model->save();
        return $this->redirect(['address/index']);
    }
    //取消全部默认
    public function cancelDefault()
    {
        $member_id = \Yii::$app->user->identity->getId();
        //取消全部默认
        $addresses = Address::findAll(['member_id' => $member_id]);
        foreach ($addresses as $address) {
            if ($address->is_default) {
                $address->is_default = 0;
                $address->save();
            }
        }
    }
}