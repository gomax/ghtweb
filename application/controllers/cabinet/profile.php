<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Profile extends Controllers_Cabinet_Base
{
	public function index()
	{
        if($this->input->post())
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_error_delimiters('', '<br />');

            $this->form_validation->set_rules('new_password', 'Новый пароль', 'trim|min_length[4]');
            $this->form_validation->set_rules('renew_password', 'Повтор нового пароля', 'trim|min_length[4]|matches[new_password]');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('protected_ip', 'IP адрес', 'trim|callback__check_valid_ip');

            if($this->form_validation->run())
            {
                $data_tmp = array(
                    'email' => $_POST['email'],
                );

                if($this->input->post('new_password'))
                {
                    $data_tmp['password'] = $this->auth->password_encript($_POST['new_password']);
                }

                $data_tmp['protected_ip'] = '';

                if($this->input->post('protected_ip'))
                {
                    $data_tmp['protected_ip'] = array();

                    $ips = explode("\n", $_POST['protected_ip']);

                    foreach($ips as $ip)
                    {
                        $ip = str_replace(array("\n", "\r"), '', trim($ip));
                        $data_tmp['protected_ip'][] = $ip;
                    }

                    $data_tmp['protected_ip'] = json_encode($data_tmp['protected_ip']);
                }


                $data_tmp['user_id'] = $this->auth->get('user_id');

                $activation_link = md5(uniqid(rand()) . $data_tmp['email']);

                $this->cache->ignore = TRUE;
                $this->cache->save('tmp/' . $activation_link, $data_tmp, $this->config->item('profile_change_time') * 60);
                $this->cache->ignore = FALSE;

                $this->load->model('email_templates_model');

                $email_tpl = $this->email_templates_model->get_template('change_profile');

                if(!$email_tpl)
                {
                    $email_tpl['text']  = 'Ошибка';
                    $email_tpl['title'] = 'Шаблон для письма не найден';
                }

                $email_tpl['text'] = strtr($email_tpl['text'], array(
                    ':site_url'        => site_url(),
                    ':login'           => $this->auth->get('login'),
                    ':activation_link' => anchor('cabinet/profile/change/' . $activation_link, 'ссылка'),
                ));

                send_mail($this->auth->get('email'), $email_tpl['title'], $email_tpl['text']);

                $this->view_data['message'] = Message::info('Необходимо подтверждение данных с email для обновления профиля, ссылка действительна ~' . $this->config->item('profile_change_time') . ' минут.');
            }

            if(validation_errors())
            {
                $this->view_data['message'] = Message::false(validation_errors());
            }
        }

        // Meta
        $this->set_meta_title('Редактирование профиля');

        $this->view_data['content'] = $this->load->view('cabinet/profile', $this->view_data, TRUE);
	}

    public function change($hash)
    {
        $cache_name = 'tmp/' . $hash;
        $data       = $this->cache->get($cache_name);

        if($data === FALSE)
        {
            $message = Message::false('Время ключа истекло');
        }
        else
        {
            if($data['user_id'] == $this->auth->get('user_id'))
            {
                unset($data['user_id']);

                $data_db_where = array(
                    'user_id' => $this->auth->get('user_id'),
                );

                if($this->users_model->edit($data, $data_db_where))
                {
                    $message = Message::true('Профиль изменен');
                    $this->cache->delete($cache_name);
                }
                else
                {
                    $message = Message::false('Ошибка! Обратитесь к Администрации сайта');
                }
            }
            else
            {
                $message = Message::false('Отказано в доступе');
            }
        }

        $this->session->set_flashdata('message', $message);
        redirect('cabinet/profile');
    }
}