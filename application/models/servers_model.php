<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Servers_model extends Crud
{
    public $table = 'servers';
    
    private $_fields = array(
        'name', 'ip', 'port', 'db_host', 'db_port', 'db_user', 'db_pass',
        'db_name', 'telnet_host', 'telnet_port', 'telnet_pass', 'login_id',
        'version', 'allow', 'fake_online', 'allow_teleport', 'teleport_time',
        'stats_allow', 'stats_cache_time', 'stats_total', 'stats_pvp', 'stats_pk', 'stats_clans',
        'stats_castles', 'stats_online',
        'stats_clan_info', 'stats_top', 'stats_rich', 'stats_count_results',
        'exp', 'sp', 'adena', 'items', 'spoil', 'q_drop', 'q_reward', 'rb', 'erb',
        'donat_allow', 'donat_item_id', 'donat_item_name', 'donat_item_cost', 'donat_table', 'donat_item_type');



    public function get_fields()
    {
        return (isset($this->_fields) ? $this->_fields : false);
    }

    public function get_all()
    {
        $cache_name = 'servers';

        if(!($servers = $this->cache->get($cache_name)))
        {
            $res = $this->get_list(0, 0, NULL, 'created_at', 'DESC');
            $servers = array();

            foreach($res as $server)
            {
                $servers[$server['id']] = $server;
            }

            if($servers)
            {
                $this->cache->save($cache_name, $servers);
            }
        }

        return $servers;
    }
}