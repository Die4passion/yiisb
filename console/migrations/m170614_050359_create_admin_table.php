<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m170614_050359_create_admin_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('admin', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique()->comment('用户名'),
            'auth_key' => $this->string(32)->notNull()->comment('认证码'),
            'password_hash' => $this->string()->notNull()->comment('密码哈希'),
            'password_reset_token' => $this->string()->unique()->comment('密码重置标识'),
            'email' => $this->string()->notNull()->unique()->comment('邮箱'),
            'status' => $this->smallInteger()->defaultValue(10)->comment('状态'),
            'created_at' => $this->integer()->comment('创建时间'),
            'updated_at' => $this->integer()->comment('修改时间'),
            'last_login_time' => $this->integer()->comment('最后登录时间'),
            'last_login_ip' => $this->char(15)->comment('最后登录ip'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('admin');
    }
}
