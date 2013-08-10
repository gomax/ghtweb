<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Controllers_Cabinet_Base extends GW_Controller
{
	public function __construct()
    {
        parent::__construct();
        
        $this->load->set_view_path($this->config->item('template'));
        
        if(!$this->auth->is_logged())
        {
            redirect('login');
        }

        $this->view_data['meta_title']       = $this->config->item('meta_title');
        $this->view_data['meta_keywords']    = $this->config->item('meta_title');
        $this->view_data['meta_description'] = $this->config->item('meta_title');


        // Проверка если логины не включены то делаю редирект на главную
        $tus = $this->uri->segment(2);
        $allowed_url = array('profile', 'logout');

        if(!$this->get_enabled_logins())
        {
            if($tus !== FALSE && !in_array($tus, $allowed_url))
            {
                $this->session->set_flashdata('message', Message::info('В данный момент сервер не доступен'));
                redirect('cabinet');
            }
        }
    }
    
    /**
     * Проверка логин сервера по ID
     */
    public function _check_login_by_id()
    {
        $logins = $this->get_enabled_logins();

        if(!isset($logins[$this->input->post('login_id')]))
        {
            $this->form_validation->set_message('_check_login_by_id', 'Сервер не найден');
            return false;
        }
        
        return true;
    }
}