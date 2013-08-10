<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Stats extends Controllers_Frontend_Base
{
    /**
     * ID сервера для которого формируется статистика
     * @var
     */
    private $_server_id;

    /**
     * Включенные типы статистики для выбранного сервера
     * @var array
     */
    private $_enabled_stats_types = array();

    /**
     * Тип статистики который надо сейчас вывести
     * @var string
     */
    private $_use_method = FALSE;

    /**
     * Настройки текущего сервера
     * @var array
     */
    private $_server_config = array();


    
    public function __construct()
    {
        parent::__construct();

        // Все включенные сервера, для них будет формироваться статистика
        /*$servers = $this->get_enabled_servers();

        if($servers)
        {
            $this->_server_id = (int) $this->uri->segment(2);

            if(!$this->_server_id || !isset($servers[$this->_server_id]))
            {
                $this->_server_id = key($servers);
            }

            $this->_server_config = $servers[$this->_server_id];

            if($this->_server_config['stats_allow'])
            {
                // Включенные типы статистики
                foreach($this->_server_config as $key => $row)
                {
                    if(strpos($key, 'stats_') !== false && $row)
                    {
                        $this->_enabled_stats_types[] = str_replace('stats_', '', $key);
                    }
                }

                $remove_keys = array('allow', 'cache_time', 'count_results');

                // Убираю ненужные элементы
                foreach($remove_keys as $key)
                {
                    $k = array_search($key, $this->_enabled_stats_types);

                    if(is_numeric($k))
                    {
                        unset($this->_enabled_stats_types[$k]);
                    }
                }

                if($this->_enabled_stats_types)
                {
                    // Тип статистики который надо вывести
                    $stats_type = $this->uri->segment(3);

                    // Проверю чтобы она была включена на сервере
                    if($this->_enabled_stats_types && !in_array($stats_type, $this->_enabled_stats_types))
                    {
                        $stats_type = $this->_enabled_stats_types[key($this->_enabled_stats_types)];
                    }

                    $this->_use_method = $stats_type;
                }
            }
            else
            {
                $this->index();
            }
        }*/
    }

	public function index()
	{
        // Включенные сервера
        $servers = $this->get_enabled_servers();

        if($servers)
        {
            // Текущий сервер
            $this->_server_id = (int) $this->uri->segment(2);

            if(!isset($servers[$this->_server_id]))
            {
                $this->_server_id = key($servers);
            }

            // Включена ли статистика
            if($servers[$this->_server_id]['stats_allow'])
            {
                $this->_server_config = $servers[$this->_server_id];
                $remove_keys = array('allow', 'cache_time', 'count_results');

                // Типы статистики
                foreach($this->_server_config as $k => $v)
                {
                    if(strpos($k, 'stats_') !== false && $v)
                    {
                        $type = str_replace('stats_', '', $k);

                        if(in_array($type, $remove_keys))
                        {
                            continue;
                        }

                        $this->_enabled_stats_types[] = str_replace('stats_', '', $k);
                    }
                }

                // Если хоть одит тип включён
                if($this->_enabled_stats_types)
                {
                    // Какой тип статистики надо показать
                    $this->_use_method = $this->uri->segment(3);

                    if(!in_array($this->_use_method, $this->_enabled_stats_types))
                    {
                        $this->_use_method = $this->_enabled_stats_types[0];
                    }

                    $method = 'stats_' . $this->_use_method;

                    if(method_exists($this, $method))
                    {
                        $content = $this->$method();
                    }
                    else
                    {
                        $message = Message::info('Выбранный тип статистики не найден');
                    }
                }
                else
                {
                    $message = Message::info('Статистика отключена');
                }
            }
            else
            {
                $message = Message::info('Статистика отключена');
            }
        }
        else
        {
            $message = Message::info('Сервер не найден');
        }

        $types = $this->_enabled_stats_types;

        if($k = array_search('clan_info', $types))
        {
            unset($types[$k]);
        }

        $view_data = array(
            'message'      => isset($message) ? $message : '',
            'stats_list'   => $types,
            'server_list'  => $servers,
            'server_id'    => $this->_server_id,
            'current_type' => $this->_use_method,
            'content'      => isset($content) ? $content : '',
        );

        $this->view_data['content'] = $this->load->view('stats/main', $view_data, TRUE);
	}
    
    /**
     * Общая статистика
     * 
     * @return string
     */
    private function stats_total()
    {
        $cache_name = 'stats/total_' . $this->_server_id;

        if(!($data = $this->cache->get($cache_name)))
        {
            $data['server_name'] = $this->_server_config['name'];

            // Рейты
            $data['rates'] = array(
                'exp'      => (int) $this->_server_config['exp'],
                'sp'       => (int) $this->_server_config['sp'],
                'adena'    => (int) $this->_server_config['adena'],
                'items'    => (int) $this->_server_config['items'],
                'spoil'    => (int) $this->_server_config['spoil'],
                'q_drop'   => (int) $this->_server_config['q_drop'],
                'q_reward' => (int) $this->_server_config['q_reward'],
                'rb'       => (int) $this->_server_config['rb'],
                'erb'      => (int) $this->_server_config['erb'],
            );


            $this->lineage->set_id($this->_server_id)->set_type('servers');

            // Версия сервера
            $server_version = $this->lineage->get_server_version();


            $online = $this->lineage->get_count_online();

            // Fake online
            if((int) $this->_server_config['fake_online'] > 0)
            {
                $online += fake_online($online, $this->_server_config['fake_online']);
            }

            $info  = array();
            $count = ($server_version == 'i' ? 5 : 6);

            for($i = 0; $i < $count; $i++)
            {
                $info[$i] = $this->lineage->get_count_race_by_id($i);
            }


            // Общая статистика
            $data['stats'] = array();

            $data['stats']['online']     = $online;
            $data['stats']['accounts']   = (int) $this->lineage->set_id($this->_server_config['login_id'])->set_type('logins')->get_count_accounts();
            $data['stats']['characters'] = (int) $this->lineage->set_id($this->_server_id)->set_type('servers')->get_count_characters();
            $data['stats']['clans']      = (int) $this->lineage->get_count_clans();
            $data['stats']['men']        = (int) $this->lineage->get_count_male();
            $data['stats']['women']      = (int) $this->lineage->get_count_female();

            $data['stats']['human']      = ($info[0] > 0 ? round($info[0] / $data['stats']['characters'] * 100) : 0);
            $data['stats']['elf']        = ($info[1] > 0 ? round($info[1] / $data['stats']['characters'] * 100) : 0);
            $data['stats']['dark_elf']   = ($info[2] > 0 ? round($info[2] / $data['stats']['characters'] * 100) : 0);
            $data['stats']['orc']        = ($info[3] > 0 ? round($info[3] / $data['stats']['characters'] * 100) : 0);
            $data['stats']['dwarf']      = ($info[4] > 0 ? round($info[4] / $data['stats']['characters'] * 100) : 0);

            if($server_version != 'i')
            {
                $data['stats']['kamael'] = ($info[5] > 0 ? round($info[5] / $data['stats']['characters'] * 100) : 0);
            }

            $data = $this->load->view('stats/stats_total', $data, TRUE);

            if((int) $this->_server_config['stats_cache_time'] > 0)
            {
                $this->cache->save($cache_name, $data, $this->_server_config['stats_cache_time'] * 60);
            }
        }

        return $data;
    }
    
    /**
     * Статистика ПВП
     * 
     * @return string 
     */
    private function stats_pvp()
    {
        $cache_name = 'stats/pvp_' . $this->_server_id;

        if(!($data = $this->cache->get($cache_name)))
        {
            $data['server_name'] = $this->_server_config['name'];
            $data['clan_info']   = $this->_server_config['stats_clan_info'];
            $data['server_id']   = $this->_server_id;


            $data['stats_content'] = $this->lineage
                ->set_id($this->_server_id)
                ->set_type('servers')
                ->get_top_pvp((int) $this->_server_config['stats_count_results']);

            $data = $this->load->view('stats/stats_pvp', $data, TRUE);
            
            if((int) $this->_server_config['stats_cache_time'] > 0)
            {
                $this->cache->save($cache_name, $data, $this->_server_config['stats_cache_time'] * 60);
            }
        }
        
        return $data;
    }
    
    /**
     * Статистика ПК
     * 
     * @return string 
     */
    private function stats_pk()
    {
        if(!($data = $this->cache->get('stats/pk_' . $this->_server_id)))
        {
            $data['server_name'] = $this->_server_config['name'];
            $data['clan_info']   = $this->_server_config['stats_clan_info'];
            $data['server_id']   = $this->_server_id;


            $data['stats_content'] = $this->lineage
                ->set_id($this->_server_id)
                ->set_type('servers')
                ->get_top_pk((int) $this->_server_config['stats_count_results']);

            $data = $this->load->view('stats/stats_pk', $data, true);
            
            if((int) $this->_server_config['stats_cache_time'] > 0)
            {
                $this->cache->save('stats/pk_' . $this->_server_id, $data, $this->_server_config['stats_cache_time'] * 60);
            }
        }
        
        return $data;
    }
    
    /**
     * Статистика кланов
     * 
     * @return string 
     */
    private function stats_clans()
    {
        if(!($data = $this->cache->get('stats/clans_' . $this->_server_id)))
        {
            $data['server_name']     = $this->_server_config['name'];
            $data['server_id']       = $this->_server_id;
            $data['stats_clan_info'] = $this->_server_config['stats_clan_info'];
            
            
            $data['stats_content'] = $this->lineage
                ->set_id($this->_server_id)
                ->set_type('servers')
                ->get_top_clans((int) $this->_server_config['stats_count_results']);
            
            $data = $this->load->view('stats/stats_clans', $data, true);
            
            if((int) $this->_server_config['stats_cache_time'] > 0)
            {
                $this->cache->save('stats/clans_' . $this->_server_id, $data, $this->_server_config['stats_cache_time'] * 60);
            }
        }
        
        return $data;
    }
    
    /**
     * Статистика замков
     * 
     * @return string 
     */
    private function stats_castles()
    {
        if(!($data = $this->cache->get('stats/castles_' . $this->_server_id)))
        {
            $data['server_name']     = $this->_server_config['name'];
            $data['server_id']       = $this->_server_id;
            $data['stats_clan_info'] = $this->_server_config['stats_clan_info'];


            $this->lineage
                ->set_id($this->_server_id)
                ->set_type('servers');

            // Замки
            $castles = $this->lineage->get_castles();

            $content = array();
            
            foreach($castles as $row)
            {
                $content[$row['id']] = array(
                    'name'        => $row['name'],
                    'castle_id'   => $row['id'],
                    'tax_percent' => $row['taxPercent'],
                    'sieg_date'   => $row['siegeDate'],
                    'owner'       => $row['clan_name'],
                    'owner_id'    => $row['clan_id'],
                    'forwards'    => array(),
                    'defenders'   => array(),
                );
            }
            
            unset($castles);
            
            // Атакующие/Защищающиеся
            $fd = $this->lineage->get_siege();
            
            if($fd)
            {
                foreach($fd as $row)
                {
                    if(isset($content[$row['castle_id']]))
                    {
                        if($row['type'] == 1)
                        {
                            $content[$row['castle_id']]['forwards'][] = $row;
                        }
                        elseif($row['type'] == 2)
                        {
                            $content[$row['castle_id']]['defenders'][] = $row;
                        }
                    }
                }
            }
            
            unset($fd);
            
            $data['stats_content'] = $content;
            
            $data = $this->load->view('stats/stats_castles', $data, true);
            
            if((int) $this->_server_config['stats_cache_time'] > 0)
            {
                $this->cache->save('stats/castles_' . $this->_server_id, $data, $this->_server_config['stats_cache_time'] * 60);
            }
        }
        
        return $data;
    }
    
    /**
     * Статистика ONLINE
     * 
     * @return string 
     */
    private function stats_online()
    {
        if(!($data = $this->cache->get('stats/online_' . $this->_server_id)))
        {
            $data['server_name'] = $this->_server_config['name'];
            $data['clan_info']   = $this->_server_config['stats_clan_info'];
            $data['server_id']   = $this->_server_id;
            
            
            $data['stats_content'] = $this->lineage
                ->set_id($this->_server_id)
                ->set_type('servers')
                ->get_top_online((int) $this->_server_config['stats_count_results']);
            
            $data = $this->load->view('stats/stats_online', $data, true);

            if((int) $this->_server_config['stats_cache_time'] > 0)
            {
                $this->cache->save('stats/online_' . $this->_server_id, $data, $this->_server_config['stats_cache_time'] * 60);
            }
        }

        return $data;
    }
    
    /**
     * Статистика TOP
     * 
     * @return string 
     */
    private function stats_top()
    {
        if(!($data = $this->cache->get('stats/top_' . $this->_server_id)))
        {
            $data['server_name'] = $this->_server_config['name'];
            $data['clan_info']   = $this->_server_config['stats_clan_info'];
            $data['server_id']   = $this->_server_id;
            
            
            $data['stats_content'] = $this->lineage
                ->set_id($this->_server_id)
                ->set_type('servers')
                ->get_top((int) $this->_server_config['stats_count_results']);

            $data = $this->load->view('stats/stats_top', $data, true);
            
            if((int) $this->_server_config['stats_cache_time'] > 0)
            {
                $this->cache->save('stats/top_' . $this->_server_id, $data, $this->_server_config['stats_cache_time'] * 60);
            }
        }
        
        return $data;
    }
    
    
    /**
     * Статистика RICH
     * 
     * @return string 
     */
    private function stats_rich()
    {
        if(!($data = $this->cache->get('stats/rich_' . $this->_server_id)))
        {
            $data['server_name'] = $this->_server_config['name'];
            $data['clan_info']   = $this->_server_config['stats_clan_info'];
            $data['server_id']   = $this->_server_id;
            
            
            $data['stats_content'] = $this->lineage
                ->set_id($this->_server_id)
                ->set_type('servers')
                ->get_top_rich((int) $this->_server_config['stats_count_results']);
            
            $data = $this->load->view('stats/stats_rich', $data, true);
            
            if((int) $this->_server_config['stats_cache_time'] > 0)
            {
                $this->cache->save('stats/rich_' . $this->_server_id, $data, $this->_server_config['stats_cache_time'] * 60);
            }
        }
        
        return $data;
    }
    
    /**
     * Статистика просмотра информации о клане
     */
    private function stats_clan_info()
    {
        $data['clan_info'] = $this->_server_config['stats_clan_info'];
        $clan_id           = (int) $this->uri->segment(4);

        // Если статистика отключена то перекидываю юзера
        if(!$data['clan_info'] || $clan_id < 1)
        {
            redirect('stats/' . $this->_server_id);
        }
        
        if(!($data = $this->cache->get('stats/clan_info_' . $clan_id . '_' . $this->_server_id)))
        {
            $data['server_name'] = $this->_server_config['name'];
            $data['server_id']   = $this->_server_id;

            $this->lineage
                ->set_id($this->_server_id)
                ->set_type('servers');


            $data['clan_name'] = '';

            // Данные клана
            $clan_data = $this->lineage
                ->get_clan_by_id($clan_id);

            if($clan_data)
            {
                $data['clan_name'] = $clan_data['clan_name'];

                $data['stats_content'] = $this->lineage
                    ->get_characters_by_clan_id($clan_id, (int) $this->_server_config['stats_count_results']);

                $data = $this->load->view('stats/stats_clan_info', $data, true);
            }
            else
            {
                $data = Message::info('Клан не найден');
            }

            if((int) $this->_server_config['stats_cache_time'] > 0)
            {
                $this->cache->save('stats/clan_info_' . $clan_id . '_' . $this->_server_id, $data, $this->_server_config['stats_cache_time'] * 60);
            }
        }
        
        return $data;
    }
}