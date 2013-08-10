<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Top_pvp
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

        if(!(int) self::$_CI->config->item('top_pvp_allow'))
        {
            $data_view['top_pvp'] = 'Модуль отключён';
        }
        elseif(!$server_list)
        {
            $data_view['top_pvp'] = 'Сервер не найден';
        }


        if(isset($data_view['top_pvp']))
        {
            return self::$_CI->load->view('top_pvp', $data_view, TRUE);
        }

        // Cache
        if(!($content = self::$_CI->cache->get('top_pvp')))
        {
            $server_id = self::$_CI->config->item('top_pvp_server_id');
            $limit     = self::$_CI->config->item('top_pvp_per_page');

            $content = self::$_CI->lineage->set_id($server_id)->set_type('servers')->get_top_pvp($limit);

            $data_view = array(
                'top_pvp' => $content,
            );

            $content = self::$_CI->load->view('top_pvp', $data_view, true);

            if((int) self::$_CI->config->item('top_pvp_cache_time'))
            {
                self::$_CI->cache->save('top_pvp', $content, self::$_CI->config->item('top_pvp_cache_time') * 60);
            }
        }

        return $content;
    }
}
