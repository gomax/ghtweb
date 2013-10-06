<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_Update6 extends GW_Migration
{
    public function up()
    {
        $prefix = $this->db->dbprefix;

        $this->db->insert('settings', array(
            'key' => 'register_allow',
            'value' => 1,
            'name' => 'Вкл/Выкл Регистрацию',
            'description' => 'Если выключена то юзера не смогут зарегистрироваться',
            'group_id' => 8,
            'type' => 'radio',
        ));
    }
    
    public function down()
    {
        $prefix = $this->db->dbprefix;

        $this->db->delete('settings', array('key' => 'register_allow'), 1);
    }
}