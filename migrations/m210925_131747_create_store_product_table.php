<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%store_product}}`.
 */
class m210925_131747_create_store_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%store_product}}', [
            'id' => $this->primaryKey(),
            'store_id' => $this->integer()->notNull(),
            'upc' => Schema::TYPE_STRING . ' NOT NULL',
            'price' => $this->double(),
            'title' => Schema::TYPE_STRING,
        ]);

        // creates index for column `store_id`
        $this->createIndex(
            'idx-store_product-store_id',
            'store_product',
            'store_id'
        );

        // add foreign key for table `store`
        $this->addForeignKey(
            'fk-store_product-store_id',
            'store_product',
            'store_id',
            'store',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `store`
        $this->dropForeignKey(
            'fk-store_product-store_id',
            'store_product'
        );

        // drops index for column `store_id`
        $this->dropIndex(
            'idx-store_product-store_id',
            'store_product'
        );

        $this->dropTable('{{%store_product}}');
    }
}
