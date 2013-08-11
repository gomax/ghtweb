<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_Update3 extends GW_Migration
{
    public function up()
    {
        $prefix = $this->db->dbprefix;

        $this->db->update('settings', array('param' => 'ipb,phpbb,smf,vanilla,vBulletin,xenForo'), array('key' => 'forum_type'), 1);
    }
    
    public function down()
    {
        $prefix = $this->db->dbprefix;

        $this->db->update('settings', array('param' => 'ipb,phpbb,smf,vanilla,vBulletin'), array('key' => 'forum_type'), 1);
    }
}