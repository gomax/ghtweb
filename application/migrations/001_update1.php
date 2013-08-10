<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_Update1 extends GW_Migration
{
    public function up()
    {
        $prefix = $this->db->dbprefix;

        $this->db->query("ALTER TABLE `" . $prefix . "users` DROP COLUMN `last_ip`");
        $this->db->query("ALTER TABLE `" . $prefix . "users` DROP COLUMN `last_login`");


        $this->db->insert('settings_group', array(
            'title' => 'Recaptcha',
            'description' => 'Recaptcha',
            'allow' => '1',
        ));

        $insert_id = $this->db->insert_id();

        $this->db->insert_batch('settings', array(
            array(
                'key'      => 'recaptcha_forgotten_password',
                'value'    => 0,
                'name'     => 'Капча при восстановлении пароля',
                'group_id' => $insert_id,
                'type'     => 'radio',
            ),
            array(
                'key'      => 'recaptcha_register',
                'value'    => 0,
                'name'     => 'Капча при регистрации',
                'group_id' => $insert_id,
                'type'     => 'radio',
            ),
            array(
                'key'      => 'recaptcha_login',
                'value'    => 0,
                'name'     => 'Капча при авторизации',
                'group_id' => $insert_id,
                'type'     => 'radio',
            ),
            array(
                'key'      => 'recaptcha_public_key',
                'value'    => '6LcvNOISAAAAADvUFjvskF8aBaJyqpZJuagj8izh',
                'name'     => 'Публичный калюч от Recaptcha',
                'group_id' => $insert_id,
                'type'     => 'input',
            ),
            array(
                'key'      => 'recaptcha_private_key',
                'value'    => '6LcvNOISAAAAAHg3Grl4JkppR4OYn15h_7NEuT0r',
                'name'     => 'Приватный калюч от Recaptcha',
                'group_id' => $insert_id,
                'type'     => 'input',
            ),
        ));


        $this->db->query("
            CREATE TABLE `" . $prefix . "users_login_logs` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `user_id` int(10) unsigned NOT NULL,
                `ip` varchar(25) NOT NULL,
                `user_agent` varchar(255) DEFAULT NULL,
                `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
                `created_at` datetime NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ");
    }
    
    public function down()
    {
        $prefix = $this->db->dbprefix;

        $this->db->query("ALTER TABLE `" . $prefix . "users` ADD COLUMN `last_ip` varchar(20) DEFAULT NULL");
        $this->db->query("ALTER TABLE `" . $prefix . "users` ADD COLUMN `last_login` datetime DEFAULT NULL COMMENT 'Последний раз авторизовывался'");

        $this->db->delete('settings_group', array(
            'title' => 'Recaptcha',
        ));

        $this->db->delete('settings', array('key' => 'recaptcha_forgotten_password'), 1);
        $this->db->delete('settings', array('key' => 'recaptcha_register'), 1);
        $this->db->delete('settings', array('key' => 'recaptcha_login'), 1);
        $this->db->delete('settings', array('key' => 'recaptcha_public_key'), 1);
        $this->db->delete('settings', array('key' => 'recaptcha_private_key'), 1);


        $this->db->query("DROP TABLE IF EXISTS `" . $prefix . "users_login_logs`");
    }
}