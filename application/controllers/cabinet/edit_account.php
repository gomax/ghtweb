<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Edit_account extends Controllers_Cabinet_Base
{
    public function index($login_id, $account)
    {
        // Если логин выключен
        if(!$this->l2_settings['logins'][$login_id]['allow'])
        {
            redirect('cabinet/accounts');
        }

        $this->load->model('accounts_model');

        $user_id = $this->auth->get('user_id');

        $data_db_where = array(
            'user_id'  => $user_id,
            'login_id' => $login_id,
            'account'  => $account,
        );

        $data = $this->accounts_model->get_row($data_db_where);

        if($data)
        {
            if(isset($_POST['submit']))
            {
                $this->load->library('form_validation');

                $this->form_validation->set_error_delimiters('', '<br />');

                $this->form_validation->set_rules('password', 'Новый пароль', 'trim|required|min_length[4]');

                if($this->form_validation->run())
                {
                    $password_type = $this->l2_settings['logins'][$login_id]['password_type'];

                    $password = pass_encode($_POST['password'], $password_type);

                    $content = $this->lineage
                        ->set_id($login_id)
                        ->set_type('logins')
                        ->change_account_password($account, $password);

                    if($content)
                    {
                        $message = Message::true('Пароль успешно изменен.');
                    }
                    else
                    {
                        $message = Message::false('Ошибка! Обратитесь к Администрации сайта');
                    }
                }

                if(validation_errors())
                {
                    $message = Message::false(validation_errors());
                }
            }
        }
        else
        {
            redirect('cabinet/accounts');
        }


        // Meta
        $this->set_meta_title('Редактирование аккаунта');

        $view_data = array(
            'message' => isset($message) ? $message : '',
        );

        $this->view_data['content'] = $this->load->view('cabinet/edit_account', $view_data, TRUE);
    }
}