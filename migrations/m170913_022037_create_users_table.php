<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m170913_022037_create_users_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'username' => $this->string(12)->notNull()->unique(),
            'auth_key' => $this->string(100),
            'balance' => $this->float(2)->notNull()->defaultValue(0),
            'lastActivity' => $this->dateTime(),
            'created_at' => $this->timestamp(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('users');
    }
}
