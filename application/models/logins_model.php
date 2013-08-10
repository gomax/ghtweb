<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Logins_model extends Crud
{
    public $table = 'logins';
    
    private $_fields = array(
        'name', 'ip', 'port', 'db_host', 'db_port', 'db_user', 'db_pass',
        'db_name', 'telnet_host', 'telnet_port', 'telnet_pass', 'version',
        'allow', 'password_type');



    public function get_fields()
    {
        return (isset($this->_fields) ? $this->_fields : false);
    }

    public function get_all()
    {
        $cache_name = 'logins';

        if(!($logins = $this->cache->get($cache_name)))
        {
            $res = $this->get_list(0, 0, NULL, 'created_at', 'DESC');
            $logins = array();

            foreach($res as $login)
            {
                $logins[$login['id']] = $login;
            }

            if($logins)
            {
                $this->cache->save($cache_name, $logins);
            }
        }

        return $logins;
    }
}