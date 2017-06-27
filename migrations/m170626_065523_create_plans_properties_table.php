<?php

use yii\db\Migration;

/**
 * Handles the creation of table `plans_properties`.
 * Has foreign keys to the tables:
 *
 * - `plans`
 */
class m170626_065523_create_plans_properties_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('plans_properties', [
            'id' => $this->primaryKey(),
            'plan_id' => $this->integer()->notNull(),
            'property_id' => $this->integer(),
            'property_type_id' => $this->integer(),
            'active_from' => $this->dateTime(),
            'active_to' => $this->dateTime(),
            'prop_value' => $this->integer(),
        ]);

        // creates index for column `plan_id`
        $this->createIndex(
            'idx-plans_properties-plan_id',
            'plans_properties',
            'plan_id'
        );

        // add foreign key for table `plans`
        $this->addForeignKey(
            'fk-plans_properties-plan_id',
            'plans_properties',
            'plan_id',
            'plans',
            'plan_id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `plans`
        $this->dropForeignKey(
            'fk-plans_properties-plan_id',
            'plans_properties'
        );

        // drops index for column `plan_id`
        $this->dropIndex(
            'idx-plans_properties-plan_id',
            'plans_properties'
        );

        $this->dropTable('plans_properties');
    }
}
