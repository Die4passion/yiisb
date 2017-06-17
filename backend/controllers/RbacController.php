<?php

namespace backend\controllers;


use backend\filters\AdminFilter;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RbacController extends Controller
{
    /**
     * 权限的操作
     */
    //添加权限
    public function actionAddPermission()
    {
        $model = new PermissionForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->addPermission()) {
            \Yii::$app->session->setFlash('success', '权限'.$model->name.'添加成功!');
            return $this->redirect(['rbac/permission-index']);
        }
        return $this->render('add-permission', ['model' => $model, 'title' => '添加权限']);
    }

    //权限列表
    public function actionPermissionIndex()
    {
        $models = \Yii::$app->authManager->getPermissions();
        return $this->render('permission-index', ['models' => $models, 'title' => '权限列表']);
    }

    //修改权限
    public function actionUpdatePermission($name)
    {
        $permission = \Yii::$app->authManager->getPermission($name);
        //判断权限是否存在
        if ($permission) {
            $model = new PermissionForm();
            //将要修改的权限赋值给表单模型
            $model->loadPermission($permission);
            if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->updatePermission($name)) {
                \Yii::$app->session->setFlash('success', '权限'.$model->name.'修改成功!');
                return $this->redirect(['rbac/permission-index']);
            }
            return $this->render('add-permission', ['model' => $model, 'title' => '修改权限']);
        } else {
            throw new NotFoundHttpException($name . '权限不存在');
        }
    }

    //删除权限
    public function actionDelPermission($name)
    {
        $permission = \Yii::$app->authManager->getPermission($name);
        //判断权限是否存在
        if ($permission) {
            \Yii::$app->authManager->remove($permission);
            \Yii::$app->session->setFlash('success', '删除权限' . $name . '成功!');
            return $this->redirect(['rbac/permission-index']);
        } else {
            throw new NotFoundHttpException($name . '权限不存在');
        }
    }
    /*
     * 角色的操作
     */
    //添加角色
    public function actionAddRole()
    {
        $model = new RoleForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->addRole()) {
            \Yii::$app->session->setFlash('success', '角色' . $model->name . '添加成功！');
            return $this->redirect(['rbac/role-index']);
        }
        return $this->render('add-role', ['model' => $model, 'title' => '添加角色']);
    }

    //角色列表
    public function actionRoleIndex()
    {
        $models = \Yii::$app->authManager->getRoles();
        return $this->render('role-index', ['models' => $models, 'title' => '角色列表']);
    }

    //修改角色
    public function actionUpdateRole($name)
    {
        $role = \Yii::$app->authManager->getRole($name);
        if ($role) {
            $model = new RoleForm();
            $model->loadRole($role);
            if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->updateRole($name)) {
                \Yii::$app->session->setFlash('success', '角色' . $model->name . '修改成功!');
                return $this->redirect(['rbac/role-index']);
            }
            return $this->render('add-role', ['model' => $model, 'title' => '修改角色']);
        } else {
            throw new NotFoundHttpException('角色' . $name . '不存在');
        }
    }

    //删除角色
    public function actionDelRole($name)
    {
        $role = \Yii::$app->authManager->getRole($name);
        if ($role) {
            \Yii::$app->authManager->remove($role);
            \Yii::$app->session->setFlash('success', '删除角色' . $name . '成功!');
            return $this->redirect(['rbac/role-index']);
        } else {
            throw new NotFoundHttpException('角色' . $name . ' 不存在');
        }
    }

    //权限规则
    public function behaviors()
    {
        return [
            'rbac' => [
                'class' => AdminFilter::className(),
            ],
        ];
    }
}
