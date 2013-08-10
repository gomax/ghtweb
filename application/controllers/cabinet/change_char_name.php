<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Change_char_name extends Controllers_Cabinet_Base
{
    public function index()
    {
        if($this->_data['server_list'])
        {
            $server_id = (int) $this->uri->segment(3);
            $char_id   = (int) $this->uri->segment(4);
            $user_id   = $this->auth->get('user_id');
            
            if(!isset($this->_data['server_list'][$server_id]) || $char_id < 1)
            {
                redirect('cabinet/game_accounts');
            }

            // Проверяю чтобы меняли своему чару имя
            if(!($char_data = $this->check_character_belongs_to_users_by_char_id($char_id, $user_id, $server_id)))
            {
                $this->session->set_flashdata('message', Message::false(lang('Можно менять имя только своему персонажу')));
                redirect('cabinet/game_accounts');
            }

            $this->_data['char_data'] = $char_data;
        }
        else
        {
            $this->_data['message'] = Message::info(lang('Сервер(а) в данный момент не доступны'));
        }

        $this->tpl(__METHOD__);
    }

    public function change()
    {
        if(!isset($_POST['submit']) || !$this->config->item('change_char_name') || !isset($this->_data['server_list'][$_POST['server_id']]) || !is_numeric($_POST['char_id']))
        {
            redirect('cabinet/game_accounts');
        }

        $server_id = (int) $_POST['server_id'];
        $char_id   = (int) $_POST['char_id'];
        $user_id   = $this->auth->get('user_id');


        if(!($char_data = $this->check_character_belongs_to_users_by_char_id($char_id, $user_id, $server_id)))
        {
            redirect('cabinet/game_accounts');
        }

        if($char_data['online'] > 0)
        {
            $this->session->set_flashdata('message', Message::info(lang('Персонаж в игре')));
        }
        elseif(preg_match('~^' . $this->config->item('change_char_name_tpl') . '$~si', $_POST['char_name']))
        {
            // Проверяю не занято ли имя
            if(!$this->check_char_name_for_exists($_POST['char_name'], $server_id))
            {
                $this->lineage->set_id($server_id)->set_type('servers');

                // Проверка хватит ли денег
                if($this->auth->get('money') < (int) $this->config->item('change_char_name_money'))
                {
                    $this->session->set_flashdata('message', Message::false(lang('У Вас не хватает баланса для совершения сделки')));
                }
                elseif($this->lineage->change_char_name($char_id, $_POST['char_name']))
                {
                    // Снимаю деньги за услугу
                    $m = ($this->auth->get('money') - $this->config->item('change_char_name_money'));
                    $this->auth->set(array('money' => $m), true);

                    $this->session->set_flashdata('message', Message::true(lang('Имя изменено')));
                }
                else
                {
                    $this->session->set_flashdata('message', Message::false(lang('Ошибка! Обратитесь к Администрации сайта')));
                }
            }
            else
            {
                $this->session->set_flashdata('message', Message::info(lang('Имя уже занято')));
            }
        }
        else
        {
            $this->session->set_flashdata('message', Message::false(lang('Имя персонажа введено не верно')));
        }

        redirect_back();
    }
}