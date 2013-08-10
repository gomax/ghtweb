<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Characters extends Controllers_Cabinet_Base
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('accounts_model');
    }
    
    
    
    public function index($login_id, $account)
    {
        // Если логин выключен
        /*if(!$this->l2_settings['logins'][$login_id]['allow'])
        {
            redirect('cabinet/accounts');
        }*/

        $user_id = $this->auth->get('user_id');
        $content = array();

        $data_db_where = array(
            'user_id'  => $user_id,
            'login_id' => $login_id,
            'account'  => $account,
        );

        $user_accounts = $this->accounts_model->get_row($data_db_where);

        // Проверка чтобы смотрел только свой аккаунт
        if($user_accounts)
        {
            $cache_name = 'cabinet/characters_' . $user_id . '_' . $login_id . '_' . $account;

            if(!($content = $this->cache->get($cache_name)))
            {
                // Все сервера которые висят на данном логине
                $servers = $this->get_servers_by_login($login_id, FALSE);
                $content = array();

                if($servers)
                {
                    foreach($servers as $server_id => $server_name)
                    {
                        $characters = $this->lineage
                            ->set_id($server_id)
                            ->set_type('servers')
                            ->get_characters_by_login($account);

                        if(!$characters)
                        {
                            continue;
                        }

                        $content[$server_id] = array(
                            'server_name' => $server_name,
                            'characters'  => $characters,
                        );
                    }
                }

                $this->cache->save($cache_name, $content, $this->config->item('cache_characters_time') * 60);
            }
        }
        else
        {
            redirect('cabinet/accounts');
        }


        if(!$content)
        {
            $message = Message::info('Персонажи не найдены');
        }

        if($this->session->flashdata('message'))
        {
            $message = $this->session->flashdata('message');
        }

        // Meta
        $this->set_meta_title('Список персонажей');

        $view_data = array(
            'message'   => isset($message) ? $message : '',
            'content'   => $content,
            'server_id' => $login_id,
        );

        $this->view_data['content'] = $this->load->view('cabinet/characters', $view_data, TRUE);
    }

    public function teleport($server_id, $char_id, $account)
    {
        $data_db_where = array(
            'user_id'  => $this->auth->get('user_id'),
            'login_id' => $this->l2_settings['servers'][$server_id]['login_id'],
            'account'  => $account,
        );

        $data = $this->accounts_model->get_row($data_db_where);

        if($data)
        {
            // Проверяю чтобы персонаж был не в игре
            $char_data = $this->lineage->set_id($server_id)->set_type('servers')->get_character_by_char_id($char_id);

            if(!$char_data)
            {
                $message = Message::false('Персонаж не найден');
            }
            elseif($char_data['online'] > 0)
            {
                $message = Message::info('Персонаж в игре');
            }
            else
            {
                $this->load->model('teleports_model');

                // Проверяю предыдущие телепорты
                $tp = $this->teleports_model->get_row(array(
                    'char_id'      => $char_id,
                    'server_id'    => $server_id,
                    'user_id'      => $data_db_where['user_id'],
                    'created_at >' => db_date(time() - $this->l2_settings['servers'][$server_id]['teleport_time'] * 60),
                ));

                if(!$tp)
                {
                    $city    = $this->config->item('list_city', 'lineage');
                    $city_id = array_rand($city, 1);
                    $city    = $city[$city_id];

                    $coordinates = $city['coordinates'];
                    $coordinates = $coordinates[array_rand($coordinates, 1)];

                    $res = $this->lineage
                        ->set_id($server_id)
                        ->set_type('servers')
                        ->change_coordinates($coordinates, $char_id);

                    if($res)
                    {
                        $this->teleports_model->del(array(
                            'created_at <' => $this->l2_settings['servers'][$server_id]['teleport_time'] * 60 - 60,
                        ));

                        $data_db = array(
                            'char_id'   => $char_id,
                            'server_id' => $server_id,
                            'city_id'   => $city_id,
                            'user_id'   => $data_db_where['user_id'],
                        );

                        $this->teleports_model->add($data_db);

                        $message = Message::true('Персонаж был телепортирован в <b>' . $city['name'] . '</b>');
                    }
                    else
                    {
                        $message = Message::false('Ошибка! Обратитесь к Администрации сайта');
                    }
                }
                else
                {
                    $message = Message::info('Вы уже недавно телепортировались');
                }
            }
        }
        else
        {
            $message = Message::false('Персонаж не найден');
        }


        $this->session->set_flashdata('message', $message);
        redirect('cabinet/characters/' . $data_db_where['login_id'] . '/' . $account);
    }
}