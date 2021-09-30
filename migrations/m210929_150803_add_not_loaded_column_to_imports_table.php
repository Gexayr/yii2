<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%imports}}`.
 */
class m210929_150803_add_not_loaded_column_to_imports_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('imports', 'not_loaded', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('imports', 'not_loaded');
    }
}
