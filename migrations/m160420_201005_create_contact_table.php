<?php

use yii\db\Migration;

class m160420_201005_create_contact_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%contact}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string(),
            'middle_name' => $this->string()
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%contact}}');
    }
}
