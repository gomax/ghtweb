<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Controllers_Frontend_Base
{
    public function __construct()
    {
        parent::__construct();
        
        if($this->auth->is_logged())
        {
            redirect('cabinet');
        }

        $this->load->model('login_attempts_model');
    }
    
	public function index()
	{
        $auth = true;

        $login_attempts_data = $this->login_attempts_model->get_data();

        if(isset($login_attempts_data['count']) && $login_attempts_data['count'] >= $this->config->item('count_failed_login_attempts'))
        {
            $auth = false;
            $this->view_data['message'] = Message::info('Для Вас вход в личный кабинет заблокирован за попытку перебора пароля');
        }

        // Праверяю время блокировки
        if($auth === false && (time() > strtotime($login_attempts_data['created_at']) + (int) $this->config->item('time_blocked_login_attempts') * 60))
        {
            $this->login_attempts_model->clear();
            $auth = true;
        }

        if($auth === true && isset($_POST['submit']))
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_error_delimiters('', '<br />');
            
            $this->form_validation->set_rules('login', 'Логин', 'trim|required|xss_clean|min_length[4]|max_length[20]');
            $this->form_validation->set_rules('password', 'Пароль', 'trim|required|min_length[4]');

            if($this->config->item('recaptcha_login'))
            {
                $this->form_validation->set_rules('recaptcha_response_field', 'Код с картинки', 'trim|required|check_recaptcha');
            }

            if($this->form_validation->run())
            {
                $login    = $this->input->post('login', true);
                $password = $this->input->post('password', true);
                
                
                $data_db_where = array(
                    'login' => $login
                );
                
                $user_data = $this->users_model->get_row($data_db_where);
                
                if($user_data)
                {
                    $allowed_ip = explode(',', $user_data['protected_ip']);

                    if($user_data['activated'] == 0)
                    {
                        $this->view_data['message'] = Message::false('Аккаунт не активирован');
                    }
                    elseif($user_data['protected_ip'] != '' && !in_array($this->input->ip_address(), $allowed_ip))
                    {
                        $this->view_data['message'] = Message::false('С Вашего IP нельзя войти в личный кабинет');
                    }
                    elseif($user_data['banned'])
                    {
                        $this->view_data['message'] = Message::false('Мастер Аккаунт заблокирован, причина: ' . $user_data['banned_reason']);
                    }
                    elseif($user_data['password'] == $this->auth->password_encript($password))
                    {
                        $this->auth->auth($user_data['user_id']);
                        $this->login_attempts_model->clear();
                        redirect('cabinet');
                    }
                    else
                    {
                        $this->view_data['message'] = Message::false('Пароль от аккаунта введен неверно');

                        $this->login_attempts_model->add_false_attempt();
                    }
                }
                else
                {
                    $this->view_data['message'] = Message::false('Аккаунт не найден');
                }
            }

            if(validation_errors())
            {
                $this->view_data['message'] = Message::false(validation_errors());
            }
        }


        $this->view_data['recaptcha'] = FALSE;

        if($this->config->item('recaptcha_login') == 1)
        {
            $this->view_data['recaptcha'] = $this->recaptcha->recaptcha_get_html();
        }

        // Meta
        $this->set_meta_title('Вход в Мастер аккаунт');

        $this->view_data['content'] = $this->load->view('login', $this->view_data, TRUE);
	}
}