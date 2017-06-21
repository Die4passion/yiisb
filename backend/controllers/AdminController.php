<?php

namespace backend\controllers;


use backend\filters\AdminFilter;
use backend\models\Admin;
use backend\models\LoginForm;
use backend\models\ChangePasswordForm;
use backend\models\RoleForm;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;

class AdminController extends Controller
{
    //增
    public function actionAdd()
    {
        $model = new Admin();
        $model->scenario = Admin::SCENARIO_REGISTER;
        $roles = new RoleForm();
        if ($model->load(\Yii::$app->request->post()) && $roles->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                $roles->userAddRoles($model->id);
                \Yii::$app->session->setFlash('success', '新增管理-->' . $model->username . '成功');
            } else {
                \Yii::$app->session->setFlash('warning', '添加失败！');
            }
            return $this->redirect(['admin/index']);
        }
        return $this->render('add', ['model' => $model, 'title' => '新增管理', 'roles' => $roles]);
    }

    //删
    public function actionDel($id)
    {
        $model = Admin::findOne(['id' => $id]);
        $model->status = 0;
        if ($model->save()) {
            \Yii::$app->session->setFlash('success', '删除狗管理 ' . $model->username . '成功!');
        } else {
            \Yii::$app->session->setFlash('warning', '删除管理员失败！');
        }
        return $this->redirect(['admin/index']);
    }

    //改
    public function actionUpdate($id)
    {
        $model = Admin::findOne(['id' => $id]);
        $roles = new RoleForm();
        $roles->userRoles = array_keys(\Yii::$app->authManager->getRolesByUser($id));
        if ($model->load(\Yii::$app->request->post()) && $roles->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                $roles->userAddRoles($model->id);
                \Yii::$app->session->setFlash('success', '修改管理-->' . $model->username . '成功');
            } else {
                \Yii::$app->session->setFlash('warning', '修改失败！');
            }
            return $this->redirect(['admin/index']);
        }
        return $this->render('add', ['model' => $model, 'title' => '修改管理', 'roles' => $roles]);
    }

    //查
    public function actionIndex()
    {
        $query = Admin::find()->where(['<>', 'status', 0]);
        $page = new Pagination([
            'defaultPageSize' => 7,
            'totalCount' => $query->count(),
        ]);
        $models = $query->orderBy('id')
            ->offset($page->offset)
            ->limit($page->limit)
            ->all();
        return $this->render('index', ['models' => $models, 'page' => $page, 'title' => '管理员列表']);
    }

    //登陆
    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            \Yii::$app->session->setFlash('success', $model->username . '：欢迎回到1024论坛 !');
            return $this->goHome();
        } else {
            return $this->render('login', ['model' => $model]);
        }
    }

    //注销
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['admin/login']);
    }

    //修改自己的密码
    public function actionChangePassword($id)
    {
        //实例化表单模型
        $model = new ChangePasswordForm();
        //查出一条数据
        $admin = Admin::findOne(['id' => $id]);
        //判断是否有post请求并赋值给￥model
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //保存新密码
            $admin->password = $model->new_password;
            $admin->save();
            \Yii::$app->session->setFlash('success', '修改密码成功');
            return $this->redirect(['admin/index']);
        } else {
            return $this->render('change-password', ['admin' => $admin, 'model' => $model, 'title' => '修改密码']);
        }
    }

    //修改别人的密码
    public function actionResetPassword($id)
    {
        $model = Admin::findOne(['id' => $id]);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', '重置管理员-->' . $model->username . '->密码成功，新密码是' . $model->password);
            } else {
                \Yii::$app->session->setFlash('warning', '操作失败！！！');
            }
            return $this->redirect(['admin/index']);
        }
        return $this->render('reset-password', ['model' => $model, 'title' => '重置密码']);
    }

    //行为
    public function behaviors()
    {
        return [
            'rbac' => [
                'class' => AdminFilter::className(),
                'only' => ['add', 'update', 'del', 'index', 'reset-password'],
            ],
        ];
    }
}