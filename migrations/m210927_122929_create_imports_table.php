<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%imports}}`.
 */
class m210927_122929_create_imports_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%imports}}', [
            'id' => $this->primaryKey(),
            'store_id' => $this->integer()->notNull(),
            'file' => Schema::TYPE_STRING . ' NOT NULL',
            'state' =>  "ENUM('NEW', 'PROCESSING', 'DONE')",
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%imports}}');
    }
}
