<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Add_account extends Controllers_Cabinet_Base
{
    public function index()
    {
        $logins = $this->get_enabled_logins();

        if(!$this->config->item('snap_game_account_allow'))
        {
            $message = Message::info('Модуль отключён');
        }
        elseif(isset($_POST['submit']))
        {
            $this->load->library('form_validation');

            $this->form_validation->set_error_delimiters('', '<br />');

            if(count($logins) > 1)
            {
                $this->form_validation->set_rules('login_id', ' Выберите сервер', 'required|trim|integer|callback__check_login_by_id');
            }

            $this->form_validation->set_rules('login',    'Логин',  'required|trim|xss_clean|min_length[' . $this->config->item('login_min_length', 'lineage') . ']|max_length[' . $this->config->item('login_max_length', 'lineage') . ']|alpha_dash');
            $this->form_validation->set_rules('password', 'Пароль', 'required|trim|xss_clean|min_length[' . $this->config->item('password_min_length', 'lineage') . ']|max_length[' . $this->config->item('password_max_length', 'lineage') . ']');

            if($this->form_validation->run())
            {
                $login_id = key($logins);

                if(count($logins) > 1)
                {
                    $login_id = (int) $_POST['login_id'];
                }

                $this->load->model('accounts_model');

                // Проверка свободен ли аккаунт
                $data_db_where = array(
                    'login_id' => $login_id,
                    'account'  => $_POST['login'],
                );

                if(!$this->accounts_model->get_row($data_db_where))
                {
                    $account_data = $this->lineage
                        ->set_id($login_id)
                        ->set_type('logins')
                        ->get_account_by_login($_POST['login']);

                    if($account_data)
                    {
                        // Проверка паролей
                        $pswd = pass_encode($_POST['password'], $this->l2_settings['logins'][$login_id]['password_type']);

                        if($account_data['password'] == $pswd)
                        {
                            $data_db = array(
                                'user_id'  => $this->auth->get('user_id'),
                                'login_id' => $login_id,
                                'account'  => $_POST['login'],
                            );

                            if($this->accounts_model->add($data_db))
                            {
                                $message = Message::true('Игровой аккаунт успешно добавлен к Вашему Мастер аккаунту');
                            }
                            else
                            {
                                $message = Message::false('Ошибка! Обратитесь к Администрации сайта');
                            }
                        }
                        else
                        {
                            $message = Message::false('Введенный Вами пароль и пароль от игрового аккаунта не совпадают');
                        }
                    }
                    else
                    {
                        $message = Message::false('Аккаунт не найден');
                    }
                }
                else
                {
                    $message = Message::false('Аккаунт принадлежит другому игроку');
                }
            }

            if(validation_errors())
            {
                $message = Message::false(validation_errors());
            }
        }


        // Meta
        $this->set_meta_title('Добавление игрового аккаунта');

        $view_data = array(
            'message'           => isset($message) ? $message : '',
            'logins'            => $logins,
            'logins_for_select' => $this->get_logins_for_select(),
        );

        $this->view_data['content'] = $this->load->view('cabinet/add_account', $view_data, TRUE);
    }
}