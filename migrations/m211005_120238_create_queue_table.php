<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%queue}}`.
 */
class m211005_120238_create_queue_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%queue}}', [
            'id' => $this->primaryKey(),
            'channel' => Schema::TYPE_STRING . ' NOT NULL',
            'job' => Schema::TYPE_TEXT . ' NOT NULL',
            'pushed_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'ttr' => Schema::TYPE_INTEGER . ' NOT NULL',
            'delay' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'priority' => Schema::TYPE_INTEGER . ' unsigned NOT NULL DEFAULT 1024',
            'reserved_at' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'attempt' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'done_at' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%queue}}');
    }
}
