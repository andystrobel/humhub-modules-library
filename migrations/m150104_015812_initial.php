<?php

class m150104_015812_initial extends EDbMigration
{

    public function up()
    {
        
        // Because uninstall was not available
        try {
            $this->createTable('library_category', array(
                'id' => 'pk',
                'title' => 'text DEFAULT NULL',
                'description' => 'text DEFAULT NULL',
                'show_sidebar' => 'boolean DEFAULT NULL',
                'sort_order' => 'int(11) DEFAULT NULL',
                    ), '');

            $this->createTable('library_item', array(
                'id' => 'pk',
                'category_id' => 'int(11) NOT NULL',
                'href' => 'text DEFAULT NULL',
                'title' => 'text DEFAULT NULL',
                'description' => 'text DEFAULT NULL',
                'date' => 'datetime DEFAULT NULL',
                'sort_order' => 'int(11) NOT NULL',
                    ), '');
        } catch (Exception $ex) {
            
        }
    }

    public function down()
    {
        echo "m150104_015812_initial does not support migration down.\n";
        return false;
    }

    /*
      // Use safeUp/safeDown to do migration with transaction
      public function safeUp()
      {
      }

      public function safeDown()
      {
      }
     */
}
