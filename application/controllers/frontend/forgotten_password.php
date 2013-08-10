<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Forgotten_password extends Controllers_Frontend_Base
{
    public function index()
	{
        if(isset($_POST['submit']))
        {
            $this->load->library('form_validation');

            $this->form_validation->set_error_delimiters('', '<br />');

            $this->form_validation->set_rules('login', 'Логин', 'trim|required|xss_clean|min_length[4]|max_length[20]|callback__check_login_exists');
            $this->form_validation->set_rules('captcha', 'Код с картинки', 'trim|required|callback__check_captcha');

            if($this->form_validation->run())
            {
                $this->load->helper('string');

                $new_password = random_string('alnum', rand(4,20));

                if($this->config->item('forgotten_password_type') == 'site')
                {
                    $data_db = array(
                        'password' => $this->auth->password_encript($new_password),
                    );

                    $data_db_where = array(
                        'login' => $_POST['login'],
                    );

                    if($this->users_model->edit($data_db, $data_db_where))
                    {
                        $message = Message::true('Ваш новый пароль: ' . $new_password);
                    }
                    else
                    {
                        $message = Message::false('Ошибка! Обратитесь к Администрации сайта');
                    }
                }
                else
                {
                    $hash = md5(uniqid(rand()));

                    $link = site_url('forgotten_password/' . $hash);

                    $this->load->model(array('email_templates_model', 'forgotten_password_model'));

                    $email_tpl = $this->email_templates_model->get_template('forgotten_password_step1');

                    if(!$email_tpl)
                    {
                        $email_tpl['text']  = 'Ошибка';
                        $email_tpl['title'] = 'Шаблон для письма не найден';
                    }

                    $email_tpl['text'] = strtr($email_tpl['text'], array(
                        ':site_url'       => site_url(),
                        ':forgotten_link' => $link,
                    ));

                    $data_db = array(
                        'key'   => $hash,
                        'email' => $this->login_data['email'],
                        'login' => $this->login_data['login'],
                    );

                    // Чищю записи
                    $this->forgotten_password_model->del(array(
                        'login' => $this->login_data['login'],
                    ));

                    if($this->forgotten_password_model->add($data_db))
                    {
                        if(send_mail($this->login_data['email'], $email_tpl['title'], $email_tpl['text']))
                        {
                            $message = Message::true('На Email указанный при регистрации отправлены инструкции по восстановлению пароля');
                        }
                        else
                        {
                            $this->forgotten_password_model->del(array(
                                'login' => $this->login_data['login'],
                            ));

                            $message = Message::false('Ошибка! Обратитесь к Администрации сайта');
                        }
                    }
                    else
                    {
                        $message = Message::false('Ошибка! Обратитесь к Администрации сайта');
                    }
                }
            }

            if(validation_errors())
            {
                $message = Message::false(validation_errors());
            }
        }


        if($this->session->flashdata('message'))
        {
            $message = $this->session->flashdata('message');
        }

        $captcha = $this->captcha->get_img();


        // Meta
        $this->set_meta_title('Восстановление пароля от Мастер аккаунта');

        $view_data = array(
            'message' => isset($message) ? $message : '',
            'captcha' => $captcha['image'],
        );

        $this->view_data['content'] = $this->load->view('forgotten_password', $view_data, TRUE);
	}
    
    public function step2($hash)
    {
        $data_db_where = array(
            'key' => $hash,
        );

        $this->load->model('forgotten_password_model');
        
        $data = $this->forgotten_password_model->get_row($data_db_where);
        
        if($data)
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
                $this->forgotten_password_model->del($data_db_where);
                
                
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
            $message = Message::false('Ключ восстановления пароля не найден');
        }        


        $this->session->set_flashdata('message', $message);
        redirect('forgotten_password');
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