<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Profile extends Controllers_Cabinet_Base
{
	public function index()
	{
        $message = '';

        if(isset($_POST['submit']))
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_error_delimiters('', '<br />');
            
            $this->form_validation->set_rules('new_password', 'Новый пароль', 'trim|min_length[4]');
            $this->form_validation->set_rules('renew_password', 'Повтор нового пароля', 'trim|min_length[4]|matches[new_password]');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('protected_ip', 'IP адрес', 'trim|callback__check_valid_ip');

            if($this->form_validation->run())
            {
                $data_db = array(
                    'email' => $_POST['email'],
                );

                if($this->input->post('new_password'))
                {
                    $data_db['password'] = $this->auth->password_encript($_POST['new_password']);
                }

                if($this->input->post('protected_ip'))
                {
                    $data_db['protected_ip'] = preg_replace('/\s+/', '', $_POST['protected_ip']);
                }

                $activation_link = md5(uniqid(rand()));

                $data_db_tmp = array(
                    'user_id' => $this->auth->get('user_id'),
                    'data'    => serialize($data_db),
                    'hash'    => $activation_link,
                );

                $this->load->model(array('email_templates_model', 'users_tmp_model'));

                $res = $this->users_tmp_model->add($data_db_tmp);

                if(is_numeric($res))
                {
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

                    $message = Message::info('Необходимо подтверждение данных с email для обновления профиля, ссылка действительна ~' . $this->config->item('profile_change_time') . ' минут.');
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

        // Meta
        $this->set_meta_title('Редактирование профиля');

        $view_data = array(
            'message' => $message,
        );

        $this->view_data['content'] = $this->load->view('cabinet/profile', $view_data, TRUE);
	}

    public function change($hash)
    {
        $this->load->model('users_tmp_model');

        $time = time() - $this->config->item('profile_change_time') * 60;

        $this->db->where('hash', $hash);
        $this->db->where('created_at >', db_date($time));
        $this->db->order_by('created_at', 'DESC');
        $hash_data = $this->db->get($this->users_tmp_model->table, 1)->row_array();

        if($hash_data)
        {
            $data_db = unserialize($hash_data['data']);

            $data_db_where = array(
                'user_id' => $this->auth->get('user_id'),
            );

            if($this->users_model->edit($data_db, $data_db_where))
            {
                $this->users_tmp_model->del(array(
                    'user_id' => $data_db_where['user_id']
                ));

                $message = Message::true('Профиль изменён');
            }
            else
            {
                $message = Message::false('Ошибка! Обратитесь к Администрации сайта');
            }
        }
        else
        {
            $message = Message::false('Ключ для изменения данных не найден');
        }

        $this->session->set_flashdata('message', $message);
        redirect('cabinet/profile');
    }
}