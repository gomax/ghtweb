<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Settings_model extends Crud
{
    public $table = 'settings';
    private $_fields = array('key', 'value', 'name', 'description', 'group_id', 'type', 'param');



    public function get_fields()
    {
        return (isset($this->_fields) ? $this->_fields : false);
    }

    /**
     * Сохранение настроек
     * 
     * @param array $data
     * 
     * @return boolean
     */
    public function edit_settings(array $data)
    {
        foreach($data as $k => $v)
        {
            $data_db = array(
                'value' => $v,
            );
            
            $data_db_where = array(
                'key' => $k,
            );
            
            $this->db->update($this->table, $data_db, $data_db_where, 1);
        }
        
        return true;
    }
    
    /**
     * Возвращает список настроек
     * 
     * @param integer $group_id: ID группы
     *
     * @return array
     */
    public function get_settings_by_group_id($group_id)
    {
        $this->db->where('group_id', $group_id);
        $settings = $this->db->get($this->table)->result_array();
        $slist = $this->l2_settings['servers'];
        $server_list = array();

        foreach($slist as $sid => $sdata)
        {
            $server_list[$sid] = $sdata['name'];
        }

        unset($slist);



        foreach($settings as $key => $val)
        {
            // Шаблоны сайта
            if($val['key'] == 'template')
            {
                $settings[$key]['param'] = get_templates();
            }
            // ID сервера по которому генерится файл online.txt
            elseif($val['key'] == 'server_id_for_online_txt')
            {
                $settings[$key]['param'] = $server_list;
            }
            // Дефолтная страница
            elseif($val['key'] == 'home_page_name')
            {
                $settings[$key]['param'] = $this->pages_model->get_page_titles();
            }
            // ТОП ПВП/ПК
            elseif($val['key'] == 'top_pvp_server_id' || $val['key'] == 'top_pk_server_id')
            {
                $res = $this->servers_model->get_list();

                $server_list = array();

                foreach($res as $server)
                {
                    $server_list[$server['id']] = $server['name'];
                }

                unset($res);

                $settings[$key]['param'] = $server_list;
            }
            else
            {
                if($val['param'] != '')
                {
                    $param = explode(',', $val['param']);
                    
                    if(is_array($param))
                    {
                        $settings[$key]['param'] = array_combine(array_values($param), $param);
                    }
                }
            }
        }
        
        return $settings;
    }
    
    /**
     * Возвращает список настроек, KEY - ключи, VALUE - значения
     * 
     * @return array
     */
    public function get_settings_list()
    {
        if(!($content = $this->cache->get('site_settings')))
        {
            $res = $this->get_list();

            $content = array();

            foreach($res as $row)
            {
                $content[$row['key']] = $row['value'];
            }
            
            $this->cache->save('site_settings', $content);
        }
        
        return $content;
    }

    /**
     * Добавляет новую запись в конфиг
     *
     * @param $array
     */
    public function add_new_item()
    {
        $data_db = elements($this->_fields, $this->input->post(), NULL);
        return $this->db->insert($this->table, $data_db);
    }
}