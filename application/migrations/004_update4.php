<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_Update4 extends GW_Migration
{
    public function up()
    {
        $prefix = $this->db->dbprefix;

        $this->db->delete('settings', array('key' => 'forgotten_password_type'), 1);
        $this->db->delete('settings', array('key' => 'forgotten_password_re_time'), 1);
        $this->db->query("DROP TABLE IF EXISTS `" . $prefix . "forgotten_password`");
    }
    
    public function down()
    {
        $prefix = $this->db->dbprefix;

        $this->db->insert('settings', array(
            'key' => 'forgotten_password_type',
            'value' => 'email',
            'name' => 'Тип восстановления',
            'description' => 'email - Пароль уйдёт на Email, site - будет показан на сайте',
            'group_id' => 5,
            'type' => 'dropdown',
            'param' => 'email,site',
        ));

        $this->db->insert('settings', array(
            'key' => 'forgotten_password_re_time',
            'value' => 15,
            'name' => 'Повторное восстановление',
            'description' => 'Через сколько пользователь сможет повторно восстановить пароль если уже пытался восстановить. В минутах',
            'group_id' => 5,
            'type' => 'input',
        ));

        $this->db->query("CREATE TABLE `" . $prefix . "forgotten_password` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `key` char(32) NOT NULL,
                `login` varchar(20) NOT NULL,
                `email` varchar(128) NOT NULL,
                `created_at` datetime NOT NULL,
                PRIMARY KEY (`id`),
                KEY `ix_key` (`key`) USING BTREE
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    }
}