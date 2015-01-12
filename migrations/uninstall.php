<?php

class uninstall extends ZDbMigration {

    public function up() {

        $this->dropTable('library_category');
        $this->dropTable('library_item');
    }

    public function down() {
        echo "uninstall does not support migration down.\n";
        return false;
    }

}