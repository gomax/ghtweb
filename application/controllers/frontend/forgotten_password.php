<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Forgotten_password extends Controllers_Frontend_Base
{
    const KEY_LIFETIME = 30; // Время жизни ключа для восстановления пароля (в минутах)




    public function index()
	{
        if($this->input->post())
        {
            $this->load->library('form_validation');

            $this->form_validation->set_error_delimiters('', '<br />');

            $this->form_validation->set_rules('login', 'Логин', 'trim|required|xss_clean|min_length[4]|max_length[20]|callback__check_login_exists');

            if($this->config->item('recaptcha_forgotten_password'))
            {
                $this->form_validation->set_rules('recaptcha_response_field', 'Код с картинки', 'trim|required|check_recaptcha');
            }

            if($this->form_validation->run())
            {
                $this->load->helper('string');

                $hash = md5(uniqid(rand()));


                $data_tmp = array(
                    'key'   => $hash,
                    'email' => $this->login_data['email'],
                    'login' => $this->login_data['login'],
                );

                $this->cache->ignore_save('tmp/forgotten_password_' . $hash, $data_tmp, self::KEY_LIFETIME * 60);


                $this->load->model('email_templates_model');

                $email_tpl = $this->email_templates_model->get_template('forgotten_password_step1');

                if(!$email_tpl)
                {
                    $email_tpl['text']  = 'Ошибка';
                    $email_tpl['title'] = 'Шаблон для письма не найден';
                }

                $email_tpl['text'] = strtr($email_tpl['text'], array(
                    ':site_url'       => site_url(),
                    ':forgotten_link' => site_url('forgotten-password/' . $hash),
                ));


                if(send_mail($this->login_data['email'], $email_tpl['title'], $email_tpl['text']))
                {
                    $this->view_data['message'] = Message::true('На Email указанный при регистрации отправлены инструкции по восстановлению пароля');
                }
                else
                {
                    $this->view_data['message'] = Message::false('Ошибка! Обратитесь к Администрации сайта');
                }
            }

            if(validation_errors())
            {
                $this->view_data['message'] = Message::false(validation_errors());
            }
        }


        if($this->session->flashdata('message'))
        {
            $this->view_data['message'] = $this->session->flashdata('message');
        }


        $this->view_data['recaptcha'] = FALSE;

        if($this->config->item('recaptcha_forgotten_password') == 1)
        {
            $this->view_data['recaptcha'] = $this->recaptcha->recaptcha_get_html();
        }


        // Meta
        $this->set_meta_title('Восстановление пароля от Мастер аккаунта');

        $this->view_data['content'] = $this->load->view('forgotten_password', $this->view_data, TRUE);
	}
    
    public function step2($hash)
    {
        $cache_name = 'tmp/forgotten_password_' . $hash;

        $data = $this->cache->get($cache_name);
        
        if($data !== FALSE)
        {
            $this->load->helper('string');
            
            $new_password = random_string('alnum', rand(4, 20));
            
            $data_db = array(
                'password' => $this->auth->password_encript($new_password),
            );
            
            $data_db_where = array(
                'login' => $data['login'],
            );
            
            if($this->users_model->edit($data_db, $data_db_where))
            {
                $this->load->model('email_templates_model');

                $email_tpl = $this->email_templates_model->get_template('forgotten_password_step2');

                if(!$email_tpl)
                {
                    $email_tpl['text']  = 'Ошибка';
                    $email_tpl['title'] = 'Шаблон для письма не найден';
                }

                $email_tpl['text'] = strtr($email_tpl['text'], array(
                    ':site_url' => site_url(),
                    ':password' => $new_password,
                ));

                $this->cache->delete($cache_name);

                if(send_mail($data['email'], $email_tpl['title'], $email_tpl['text']))
                {
                    $message = Message::true('На Email указанный при регистрации отправлен новый пароль');
                }
                else
                {
                    $message = Message::false('Ошибка! Обратитесь к Администрации сайта');
                }
            }
            else
            {
                $message = Message::false('Ошибка! Обратитесь к Администрации сайта');
            }
        }
        else
        {
            $message = Message::false('Ключ для восстановления пароля не найден');
        }        


        $this->session->set_flashdata('message', $message);
        redirect('forgotten-password');
    }

    public function _check_login_exists($login)
    {
        $data_db_where = array(
            'login' => $login,
        );

        $this->login_data = $this->users_model->get_row($data_db_where);

        if(!$this->login_data)
        {
            $this->form_validation->set_message('_check_login_exists', 'Аккаунт не найден');
            return FALSE;
        }

        return TRUE;
    }
}