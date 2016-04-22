<?php

use yii\db\Migration;

class m160420_201026_create_phone_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%phone}}', [
            'id' => $this->primaryKey(),
            'phone' => $this->string(),
            'contact_id' => $this->integer()->notNull()
        ]);

        //$this->addPrimaryKey('pk_phone','{{%phone}}',['phone','contact_id']);

        $this->addForeignKey('fk_phone_contact_id','{{%phone}}','contact_id','{{%contact}}','id','CASCADE','RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%phone}}');
    }
}
