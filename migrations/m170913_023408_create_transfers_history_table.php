<?php

use yii\db\Migration;

/**
 * Handles the creation of table `transfers_history`.
 * Has foreign keys to the tables:
 *
 * - `users`
 */
class m170913_023408_create_transfers_history_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('transfers_history', [
            'id' => $this->primaryKey(30),
            'user_id' => $this->integer(11)->notNull(),
            'sum' => $this->decimal(6,2)->notNull()->defaultValue(0),
            'transfer_time' => $this->timestamp()->notNull(),
            'user_received_transfer' => $this->integer(11)->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-transfers_history-user_id',
            'transfers_history',
            'user_id'
        );

        // add foreign key for table `users`
        $this->addForeignKey(
            'fk-transfers_history-user_id',
            'transfers_history',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `users`
        $this->dropForeignKey(
            'fk-transfers_history-user_id',
            'transfers_history'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-transfers_history-user_id',
            'transfers_history'
        );

        $this->dropTable('transfers_history');
    }
}
