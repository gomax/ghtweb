<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Server_status
{
    private static $_CI;



    public function __construct()
    {
        self::$_CI =& get_instance();
    }

    public static function get()
    {
        $data_view = array();

        // Проверка наличия серверов
        $server_list = self::$_CI->get_enabled_servers();

        if(!self::$_CI->config->item('server_status_allow'))
        {
            $data_view['server_status'] = 'Модуль отключён';
        }
        elseif(!$server_list)
        {
            $data_view['server_status'] = 'Сервер не найден';
        }


        if(isset($data_view['server_status']))
        {
            return self::$_CI->load->view('server_status', $data_view, TRUE);
        }


        // Cache
        if(!($content = self::$_CI->cache->get('server_status')))
        {
            // Helper
            self::$_CI->load->helper('server_status');


            $total_online             = 0;
            $server_id_for_online_txt = (int) self::$_CI->config->item('server_id_for_online_txt');

            if(!isset($server_list[$server_id_for_online_txt]))
            {
                $server_id_for_online_txt = key($server_list);
            }


            foreach($server_list as $server_id => $server_data)
            {
                $host        = $server_data['ip'];
                $port        = (int) $server_data['port'];
                $fake_online = (int) $server_data['fake_online'];
                $online      = (int) self::$_CI->lineage->set_id($server_id)->set_type('servers')->get_count_online();

                // Fake online
                if($fake_online > 0)
                {
                    $online = fake_online($online, $fake_online);
                }

                $total_online += $online;

                // Online.txt
                if($server_id_for_online_txt == $server_id)
                {
                    if(is_file(FCPATH . 'online.txt'))
                    {
                        file_put_contents(FCPATH . 'online.txt', $online);
                    }
                }

                $login_cfg  = self::$_CI->l2_settings['logins'][self::$_CI->l2_settings['servers'][$server_id]['login_id']];
                $login_host = $login_cfg['ip'];
                $login_port = $login_cfg['port'];

                $content[$server_id]['server_status'] = check_port($host, $port);
                $content[$server_id]['login_status']  = check_port($login_host, $login_port);
                $content[$server_id]['online'] = $online;
                $content[$server_id]['name']   = $server_data['name'];
            }
            
            $data_view = array(
                'server_status' => $content,
                'total_online'  => $total_online,
            );

            $content = self::$_CI->load->view('server_status', $data_view, TRUE);

            if((int) self::$_CI->config->item('cache_server_status'))
            {
                self::$_CI->cache->save('server_status', $content, self::$_CI->config->item('cache_server_status') * 60);
            }
        }
        
        return $content;
    }
}
