<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Register_account extends Controllers_Cabinet_Base
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('accounts_model');
        $this->logins = $this->get_enabled_logins();
    }
    
    
	public function index()
	{
        $prefixes = get_game_account_prefixes();


        if($this->input->post())
        {
            $this->load->library('form_validation');

            $this->form_validation->set_error_delimiters('', '<br />');

            if(count($this->logins) > 1)
            {
                $this->form_validation->set_rules('login_id', 'Выберите сервер', 'trim|required|integer|callback__check_login_by_id');
            }

            if($prefixes !== FALSE)
            {
                $this->form_validation->set_rules('prefix', 'Префикс', 'strip_tags|xss_clean|trim|required|exact_length[' . $this->config->item('game_account_prefix_length') . ']');
            }

            $this->form_validation->set_rules('login',    'Логин',  'xss_clean|trim|required|min_length[' . $this->config->item('login_min_length', 'lineage') . ']|max_length[' . $this->config->item('login_max_length', 'lineage') . ']|alpha_dash');
            $this->form_validation->set_rules('password', 'Пароль', 'xss_clean|trim|required|min_length[' . $this->config->item('password_min_length', 'lineage') . ']|max_length[' . $this->config->item('password_max_length', 'lineage') . ']');

            if($this->form_validation->run())
            {
                $login_id = key($this->logins);

                if(count($this->logins) > 1)
                {
                    $login_id = (int) ($this->input->post('login_id') ? $this->input->post('login_id') : $login_id);
                }

                // Проверяю лимит на создание аккаунтов
                $count_accounts = $this->accounts_model->get_count(array(
                    'user_id'  => $this->auth->get('user_id'),
                    'login_id' => $login_id,
                ));

                if($count_accounts >= (int) $this->config->item('count_game_accounts_allowed'))
                {
                    $message = Message::info('Вы достигли лимита на создание игровых аккаунтов');
                }
                else
                {
                    $login           = $_POST['login'];
                    $password        = $_POST['password'];

                    $password_encode = pass_encode($password, $this->l2_settings['logins'][$login_id]['password_type']);

                    // Префикс
                    if($prefixes !== FALSE)
                    {
                        $login  = strtolower($this->input->post('prefix')) . $this->config->item('game_account_prefix_separator') . $login;
                    }

                    // Проверяю чтобы аккаунт был свободен
                    $data_db_where = array(
                        'account'  => $login,
                        'login_id' => $login_id,
                    );

                    $check = $this->accounts_model->get_row($data_db_where);

                    if(!$check)
                    {
                        // Ищю такой аккаунт в БД сервера
                        $check = $this->lineage
                            ->set_id($login_id)
                            ->set_type('logins')
                            ->get_account_by_login($login);

                        if(!$check)
                        {
                            // Записываю новый аккаунт в БД сайта и сервера
                            $data_db = array(
                                'user_id'  => $this->auth->get('user_id'),
                                'login_id' => $login_id,
                                'account'  => $login,
                            );

                            if(($acc_id = $this->accounts_model->add($data_db)))
                            {
                                // Добавляю на сервер
                                $res = $this->lineage
                                    ->set_id($login_id)
                                    ->set_type('logins')
                                    ->insert_account($login, $password_encode);

                                if($res)
                                {
                                    $message = Message::true('Игровой аккаунт <b>' . $login . '</b> создан');
                                }
                                elseif($this->lineage->get_errors())
                                {
                                    $this->accounts_model->del(array(
                                        'id' => $acc_id,
                                    ));

                                    $message = Message::false($this->lineage->get_errors());
                                }
                                else
                                {
                                    $this->accounts_model->del(array(
                                        'id' => $acc_id,
                                    ));

                                    $message = Message::false('Ошибка! Обратитесь к Администрации сайта1');
                                }
                            }
                            else
                            {
                                $message = Message::false('Ошибка! Обратитесь к Администрации сайта');
                            }
                        }
                        else
                        {
                            $message = Message::false('Такой логин уже существует');
                        }
                    }
                    else
                    {
                        $message = Message::false('Такой логин уже существует');
                    }
                }
            }
        }

        if(validation_errors())
        {
            $message = Message::false(validation_errors());
        }


        // Meta
        $this->set_meta_title('Создание игрового аккаунта');

        $view_data = array(
            'message'           => isset($message) ? $message : '',
            'logins'            => $this->logins,
            'logins_for_select' => $this->get_logins_for_select(),
            'prefixes'          => $prefixes,
        );

        $this->view_data['content'] = $this->load->view('cabinet/register_account', $view_data, TRUE);
	}
}