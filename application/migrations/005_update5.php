<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_Update5 extends GW_Migration
{
    public function up()
    {
        $prefix = $this->db->dbprefix;

        $this->db->update('settings', array(
            'description' => '<b>alnum</b> - Строка с цифрами и буквами, <b>alpha</b> - Строка с буквами, <b>numeric</b> - Числовая строка, <b>nozero</b> - Числовая срока без нулей',
            'param' => 'alnum,alpha,numeric,nozero',
        ), array('key' => 'game_account_prefix_type'), 1);
    }
    
    public function down()
    {
        $prefix = $this->db->dbprefix;
    }
}