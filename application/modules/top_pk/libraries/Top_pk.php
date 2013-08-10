<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Top_pk
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

        if(!(int) self::$_CI->config->item('top_pk_allow'))
        {
            $data_view['top_pk'] = 'Модуль отключён';
        }
        elseif(!$server_list)
        {
            $data_view['top_pk'] = 'Сервер не найден';
        }


        if(isset($data_view['top_pk']))
        {
            return self::$_CI->load->view('top_pk', $data_view, TRUE);
        }

        // Cache
        if(!($content = self::$_CI->cache->get('top_pk')))
        {
            $server_id = (int) self::$_CI->config->item('top_pk_server_id');
            $limit     = (int) self::$_CI->config->item('top_pk_per_page');

            $content = self::$_CI->lineage->set_id($server_id)->set_type('servers')->get_top_pk($limit);

            $data_view = array(
                'top_pk' => $content,
            );

            $content = self::$_CI->load->view('top_pk', $data_view, true);

            if((int) self::$_CI->config->item('top_pk_cache_time'))
            {
                self::$_CI->cache->save('top_pk', $content, self::$_CI->config->item('top_pk_cache_time') * 60);
            }
        }

        return $content;
    }
}
