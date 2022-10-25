<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%news}}`.
 */
class m221025_073616_create_news_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%news}}', [
            'id' => $this->primaryKey(),
            'title' => $this->text()->notNull(),
            'description' => $this->text()->notNull(),
            'img' => $this->string(),
            'owner_id' => $this->string()->notNull(),
            'created_at' => $this->dateTime()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%news}}');
    }
}
