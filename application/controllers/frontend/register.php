<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends Controllers_Frontend_Base
{
	public function index()
	{
        if($this->input->post() && config('register_allow') == 1)
        {
            $this->load->library('form_validation');

            $this->form_validation->set_error_delimiters('', '<br />');

            $this->form_validation->set_rules('login', 'Логин', 'trim|required|xss_clean|min_length[4]|max_length[20]|callback__check_user_login');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback__check_user_email');
            $this->form_validation->set_rules('password', 'Пароль', 'trim|required|min_length[4]');
            $this->form_validation->set_rules('repassword', 'Повтор пароля', 'trim|required|matches[password]');

            if($this->config->item('recaptcha_register'))
            {
                $this->form_validation->set_rules('recaptcha_response_field', 'Код с картинки', 'trim|required|check_recaptcha');
            }


            if($this->form_validation->run())
            {
                $login    = $this->input->post('login', true);
                $password = $this->input->post('password', true);
                $email    = $this->input->post('email', true);

                $email_activation = $this->config->item('activated_account_by_email');

                $activated_hash      = NULL;
                $activated_hash_time = NULL;

                $this->load->helper('string');

                if($email_activation)
                {
                    $this->load->helper('string');

                    $activated_hash      = md5(random_string('alnum', 15));
                    $activated_hash_time = db_date();
                }

                $referrer_link = random_string('alnum', 15);


                $data_db = array(
                    'login'               => $login,
                    'password'            => $this->auth->password_encript($password),
                    'email'               => $email,
                    'activated'           => ($email_activation ? '0' : '1'),
                    'activated_hash'      => $activated_hash,
                    'activated_hash_time' => $activated_hash_time,
                );


                $insert_id = $this->users_model->add($data_db);

                if($insert_id)
                {
                    $this->load->model('email_templates_model');

                    if($email_activation)
                    {
                        $activation_link = site_url('register/activation/' . $activated_hash);

                        $email_tpl = $this->email_templates_model->get_template('register_email_activation');

                        if(!$email_tpl)
                        {
                            $email_tpl['text']  = 'Ошибка';
                            $email_tpl['title'] = 'Шаблон для письма не найден';
                        }

                        $email_tpl['text'] = strtr($email_tpl['text'], array(
                            ':site_url'        => site_url(),
                            ':activation_link' => $activation_link,
                        ));

                        $this->view_data['message'] = Message::true('Регистрация прошла удачно!<br />На email <b>' . $email . '</b> отправлены инструкции по активации аккаунта');
                    }
                    else
                    {
                        $email_tpl = $this->email_templates_model->get_template('register');

                        if(!$email_tpl)
                        {
                            $email_tpl['text']  = 'Ошибка';
                            $email_tpl['title'] = 'Шаблон для письма не найден';
                        }

                        $email_tpl['text'] = strtr($email_tpl['text'], array(
                            ':site_url' => site_url(),
                            ':login'    => $data_db['login'],
                            ':password' => $password,
                        ));

                        $this->view_data['message'] = Message::true('Регистрация прошла удачно, приятной игры');
                    }

                    send_mail($data_db['email'], $email_tpl['title'], $email_tpl['text']);
                }
                else
                {
                    $this->view_data['message'] = Message::false('Ошибка! Обратитесь к Администрации сайта');
                }

            }

            if(validation_errors())
            {
                $this->view_data['message'] = Message::false(validation_errors())  ;
            }
        }

        if($this->session->flashdata('message'))
        {
            $this->view_data['message'] = $this->session->flashdata('message');
        }

        $this->view_data['recaptcha'] = FALSE;

        if($this->config->item('recaptcha_register') == 1)
        {
            $this->view_data['recaptcha'] = $this->recaptcha->recaptcha_get_html();
        }


        // Meta
        $this->set_meta_title('Регистрация Мастер аккаунта');

        $this->view_data['content'] = $this->load->view('register', $this->view_data, TRUE);
	}
    
    /**
     * Активация аккаунта
     */
    public function activation($activation_hash)
    {
        $data_db_where = array(
            'activated_hash' => $activation_hash,
            'activated'      => 0,
        );
        
        
        $user_data = $this->users_model->get_row($data_db_where);

        if($user_data)
        {
            $time_for_account_activation = (time() - $this->config->item('time_for_account_activation') * 60);
            
            if(strtotime($user_data['activated_hash_time']) > $time_for_account_activation)
            {
                $data_db = array(
                    'activated_hash'      => NULL,
                    'activated_hash_time' => NULL,
                    'activated'           => 1,
                );
                
                if($this->users_model->edit($data_db, $data_db_where, 1))
                {
                    $message = Message::true('Аккаунт активирован');
                }
                else
                {
                    $message = Message::false('Ошибка! Обратитесь к Администрации сайта');
                }
            }
            else
            {
                // Timeout
                $message = Message::false('Ключ для активации аккаунта истёк');
            }
        }
        else
        {
            $message = Message::false('Ключ активации аккаунта не найден');
        }

        $this->session->set_flashdata('message', $message);
        redirect('register');
    }
}