<?php

namespace backend\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;
use yii\web\NotFoundHttpException;

class RoleForm extends Model
{
    public $name;
    public $description;
    public $permissions = [];//权限s
    public $userRoles = [];

    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['permissions', 'userRoles'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '角色名',
            'description' => '描述',
            'permissions' => '选择权限吧：',
            'userRoles' => '选择角色吧：'
        ];
    }

    //获取所有权限选项
    public static function getPermissionOptions()
    {
        $authManager = \Yii::$app->authManager;
        return ArrayHelper::map($authManager->getPermissions(), 'name', 'description');
    }

    //获取所有角色选项
    public static function getRolesOptions()
    {
        $authManager = \Yii::$app->authManager;
        return ArrayHelper::map($authManager->getRoles(), 'name', 'name');
    }

    //添加角色
    public function addRole()
    {
        $authManager = \Yii::$app->authManager;
        //判断角色是否存在
        if ($authManager->getRole($this->name)) {
            $this->addError('name', '角色' . $this->name . '已存在');
        } else {
            $role = $authManager->createRole($this->name);
            $role->description = $this->description;
            //保存到数据库
            if ($authManager->add($role)) {
                foreach ($this->permissions as $permissionName) {
                    $permission = $authManager->getPermission($permissionName);
                    if ($permission) {
                        $authManager->addChild($role, $permission);
                    }
                }
                return true;
            }
        }
        return false;
    }

    //找出角色内容权限
    public function loadRole(Role $role)
    {
        $this->name = $role->name;
        $this->description = $role->description;
        //获取该角色对应的权限
        $permissions = \Yii::$app->authManager->getPermissionsByRole($role->name);
        foreach ($permissions as $permission) {
            $this->permissions[] = $permission->name;
        }
    }

    //更新角色
    public function updateRole($name)
    {
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        //给角色赋值
        $role->name = $this->name;
        $role->description = $this->description;
        //检查用户名是否被修改，修改后是否已经存在
        if ($name != $this->name && $authManager->getRole($this->name)) {
            $this->addError('name', '角色' . $this->name . '已存在');
        } elseif ($authManager->update($name, $role)) {
            //去掉所有与改角色关联的权限
            $authManager->removeChildren($role);
            //然后重新关联该角色的权限
            foreach ($this->permissions as $permissionName) {
                $permission = $authManager->getPermission($permissionName);
                if ($permission) {
                    $authManager->addChild($role, $permission);
                }
            }
            return true;
        } else {
            return false;
        }
        return false;
    }

    //管理员关联角色
    public function userAddRoles($id)
    {
        $authManager = \Yii::$app->authManager;
        if ($this->userRoles){
            //去掉当前用户的所有角色
            $authManager->revokeAll($id);
            //遍历选中的角色，分配给当前用户
            foreach ($this->userRoles as $roleName) {
                $role = $authManager->getRole($roleName);
                $authManager->assign($role, $id);
            }
            return true;
        } else {
            throw new NotFoundHttpException('操作有误');
        }
    }
}