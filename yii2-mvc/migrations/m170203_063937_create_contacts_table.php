<?php

use yii\db\Migration;

/**
 * Handles the creation of table `contacts`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m170203_063937_create_contacts_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('contacts', [
            'id' => $this->primaryKey(),
            'owner_id' => $this->integer()->notNull(),
            'name' => $this->string(),
            'email' => $this->string(),
        ]);

        // creates index for column `owner_id`
        $this->createIndex(
            'idx-contacts-owner_id',
            'contacts',
            'owner_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-contacts-owner_id',
            'contacts',
            'owner_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-contacts-owner_id',
            'contacts'
        );

        // drops index for column `owner_id`
        $this->dropIndex(
            'idx-contacts-owner_id',
            'contacts'
        );

        $this->dropTable('contacts');
    }
}
