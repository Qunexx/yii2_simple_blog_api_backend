<?php

use yii\db\Migration;

/**
 * Class m241225_211826_add_accessTokens_table
 */
class m241225_211826_add_accessTokens_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%accessTokens}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'token' => $this->string()->notNull()->unique(),
            'created_at' => $this->timestamp()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-access_tokens-user_id',
            '{{%accessTokens}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-access_tokens-user_id', '{{%accessTokens}}');
        $this->dropTable('{{%accessTokens}}');
    }
}
