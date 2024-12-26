<?php

use yii\db\Migration;

/**
 * Class m241226_122501_add_userPosts_table
 */
class m241226_122501_add_userPosts_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%userPosts}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'text' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-userPosts-user_id',
            '{{%userPosts}}',
            'user_id',
            '{{%user}}',
            'id',
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-user_post-user_id', '{{%userPosts}}');
        $this->dropTable('{{%userPosts}}');
    }
}
