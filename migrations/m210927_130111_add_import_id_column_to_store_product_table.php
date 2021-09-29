<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%store_product}}`.
 */
class m210927_130111_add_import_id_column_to_store_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('store_product', 'import_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('store_product', 'import_id');
    }
}
