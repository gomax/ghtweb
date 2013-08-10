<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Settings extends Controllers_Backend_Base
{
    public $_field_types = array('dropdown', 'input', 'radio');



    public function __construct()
    {
        parent::__construct();
        
        $class = strtolower(__CLASS__);
        $this->_model = $class . '_model';
        $this->_view  = $class;
        
        
        $this->load->model($this->_model);
        
        $this->load->library('table');
    }
    
    // @TODO Не используется
	public function index()
	{
        $this->load->model('settings_group_model');
        
        $view_data = array(
            'content' => $this->settings_group_model->get_list(0, 0, array('allow' => 1)),
        );

        $this->view_data['content'] = $this->load->view('users/index', $view_data, TRUE);
    }
    
    public function group()
    {
        $id = (int) $this->uri->segment(4);
        
        if($id < 1)
        {
            redirect('backend/' . $this->_view);
        }
        
        // Save
        if(isset($_POST['submit']))
        {
            $posts = $this->input->post();
        
            unset($posts['submit']);

            if($this->settings_model->edit_settings($posts))
            {
                $this->cache->delete('site_settings');
                $message = Message::true('Настройки сохранены');
            }
            else
            {
                $message = Message::false('Ошибка! Не удалось записать данные в БД');
            }
        }

        $this->load->model('settings_group_model');

        $view_data = array(
            'group_name' => $this->settings_group_model->get_row(array('id' => $id)),
            'content'    => $this->settings_model->get_settings_by_group_id($id),
            'message'    => isset($message) ? $message : '',
        );

        $this->view_data['content'] = $this->load->view('settings/group', $view_data, TRUE);
    }

    /**
     * Добавление настройки
     *
     * @return void
     */
    public function add()
    {
        // Save
        if(isset($_POST['submit']))
        {
            $this->load->library('form_validation');

            $this->form_validation->set_error_delimiters('', '<br>');

            $this->form_validation->set_rules('key', 'Ключ', 'required|trim|alpha_dash|max_length[54]');
            $this->form_validation->set_rules('value', 'Значение', 'required|trim|max_length[255]|xss_clean');
            $this->form_validation->set_rules('name', 'Название', 'required|trim|max_length[128]|xss_clean');
            $this->form_validation->set_rules('description', 'Описание', 'trim|max_length[255]|xss_clean');
            $this->form_validation->set_rules('group_id', 'Раздел', 'required|trim|is_natural_no_zero|callback__check_group');
            $this->form_validation->set_rules('type', 'Тип поля', 'required|trim|callback__check_type');

            if($_POST['type'] == 'dropdown')
            {
                $this->form_validation->set_rules('param', 'Параметры', 'required|trim');
            }

            if($this->form_validation->run())
            {
                if($this->settings_model->add_new_item())
                {
                    $message = Message::true('Настройка добавлена');
                }
                else
                {
                    $message = Message::false('Ошибка добавления');
                }
            }
        }

        $view_data = array(
            'message'        => isset($message) ? $message : '',
            'field_types'    => $this->_field_types,
            'settings_group' => $this->view_data['settings_group'],
        );

        $this->view_data['content'] = $this->load->view('settings/add', $view_data, TRUE);
    }

    public function _check_group()
    {
        if(!isset($this->view_data['settings_group'][$_POST['group_id']]))
        {
            $this->form_validation->set_message('_check_group', 'Раздела не существует');
            return false;
        }

        return true;
    }

    public function _check_type($value)
    {
        if(!in_array($value, $this->_field_types))
        {
            $this->form_validation->set_message('_check_type', 'Выбранный тип поля не существует');
            return false;
        }

        return true;
    }
}