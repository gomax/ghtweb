<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_Update2 extends GW_Migration
{
    public function up()
    {
        $prefix = $this->db->dbprefix;

        $this->db->query("DROP TABLE IF EXISTS `" . $prefix . "users_tmp`");
    }
    
    public function down()
    {
        $prefix = $this->db->dbprefix;

        $this->db->query("CREATE TABLE `" . $prefix . "users_tmp` (
            `user_id` int(10) unsigned NOT NULL,
            `data` text NOT NULL,
            `hash` char(32) NOT NULL,
            `created_at` datetime NOT NULL,
            KEY `ix_hash` (`hash`) USING BTREE
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    }
}