<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Logins extends Controllers_Backend_Base
{
    public function __construct()
    {
        parent::__construct();

        $class = strtolower(__CLASS__);
        $this->_model = $class . '_model';
        $this->_view  = $class;
        
        $this->load->model($this->_model);
        
        // $this->_data['field_data'] = $this->{$this->_model}->get_fields();
    }
    
    public function index()
    {
        $view_data = array(
            'content' => $this->l2_settings['logins'],
        );

        $this->view_data['content'] = $this->load->view('logins/index', $view_data, TRUE);
    }
    
    public function del()
    {
        $id = (int) $this->uri->segment(4);
        
        if($id < 1)
        {
            redirect('backend/' . $this->_view);
        }
        
        $data_db_where = array(
            'id' => $id
        );
        
        $this->{$this->_model}->del($data_db_where, 1);

        $this->clear_cache();
        
        $this->session->set_flashdata('message', Message::true('Логин удален'));
        redirect('backend/' . $this->_view);
    }

    public function add()
    {
        if(isset($_POST['submit']))
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_error_delimiters('', '');
            
            if($this->form_validation->run('backend_logins'))
            {
                $data_db = elements($this->{$this->_model}->get_fields(), $this->input->post(), NULL);
                
                if($this->{$this->_model}->add($data_db))
                {
                    $this->clear_cache();

                    $message = Message::true('Логин добавлен');
                }
                else
                {
                    $message = Message::true('Ошибка! Не удалось записать данные в БД');
                }
            }
        }


        $view_data = array(
            'message' => isset($message) ? $message : '',
        );

        $this->view_data['content'] = $this->load->view('logins/add', $view_data, TRUE);
    }
    
    public function edit()
    {
        $id = (int) $this->uri->segment(4);
        
        $data_db_where = array(
            'id' => $id
        );
        

        // Save
        if(isset($_POST['submit']))
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_error_delimiters('', '');
            
            if($this->form_validation->run('backend_logins'))
            {
                $data_db = elements($this->{$this->_model}->get_fields(), $this->input->post(), NULL);
                
                if($this->{$this->_model}->edit($data_db, $data_db_where))
                {
                    $this->clear_cache();

                    $message = Message::true('Изменения сохранены');
                }
                else
                {
                    $message = Message::true('Ошибка! Не удалось записать данные в БД');
                }
            }
        }
        
        
        $view_data = array(
            'message' => isset($message) ? $message : '',
            'content' => $this->l2_settings['logins'][$id],
        );

        if(!$view_data['content'])
        {
            redirect('backend/' . $this->_view);
        }

        $this->view_data['content'] = $this->load->view('logins/edit', $view_data, TRUE);
    }
    
    public function _check_password_encode_type($password)
    {
        if(array_search($password, $this->config->item('password_type', 'lineage')) === false)
        {
            $this->form_validation->set_message('_check_password_encode_type', 'Неправильный тип шифровани пароля');
            return false;
        }
        
        return true;
    }
    
    public function stop()
    {
        $id = (int) $this->uri->segment(4);
        
        if($id < 1)
        {
            redirect('backend/' . $this->_view);
        }
        
        $allow = ($this->uri->segment(5) == 'off' ? '0' : '1');
        
        $data_db_where = array(
            'id' => $id
        );
        
        $data_db = array(
            'allow' => $allow,
        );
        
        $this->{$this->_model}->edit($data_db, $data_db_where);
        
        $this->clear_cache();

        $msg = ($allow == 1 ? 'вкл' : 'выкл');
        $this->session->set_flashdata('message', Message::true($msg));
        redirect('backend/' . $this->_view);
    }

    private function clear_cache()
    {
        $this->cache->delete('logins');
        $this->cache->delete('servers');
        $this->cache->delete('top_pvp');
        $this->cache->delete('top_pk');
        $this->cache->delete('stats/*');
    }
}