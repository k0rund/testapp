<?php

use yii\db\Migration;

/**
 * Handles the creation of table `plans`.
 */
class m170626_065434_create_plans_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('plans', [
            'id' => $this->primaryKey(),
            'plan_id' => $this->integer()->notNull()->unique(),
            'plan_name' => $this->string(),
            'plan_group_id' => $this->integer(),
            'active_from' => $this->dateTime(),
            'active_to' => $this->dateTime(),
            'company_id' => $this->integer(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('plans');
    }
}
