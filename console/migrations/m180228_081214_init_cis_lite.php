<?php

use yii\db\Migration;

class m180228_081214_init_cis_lite extends Migration
{
    public function up()
    {
        $this->insert('sysx_authentication_method', [
            'authentication_method_id' => 1,
            'name' => 'Local Database',
            'authentication_string' => 'DATABASE'
        ]);

        $this->insert('sysx_user', [
            'sysx_key' => 'EWpd9wHy1YZpmGTSsP3_QFW5loktTIPU',
            'authentication_method_id' => 1,
            'username' => 'root',
            'auth_key' => 'fxiViURunAzSxsCjfgbDseq4mFzcHX-L',
            'password_hash' => '$2y$13$HW588THKyGY4JHlNIPbjsuLUQjrLfKB0uWuDA7/eOAdgef/NUeYHa',
            'email' => 'root@local.host',
            'status' => 1
        ]);

    }

    public function down()
    {
        echo "m180228_081214_init_cis_lite cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
