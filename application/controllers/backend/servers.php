<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Servers extends Controllers_Backend_Base
{
    public function __construct()
    {
        parent::__construct();
        
        $class = strtolower(__CLASS__);
        $this->_model = $class . '_model';
        $this->_view  = $class;
        
        $this->load->model($this->_model);
    }
    
    public function index()
    {
        $view_data = array(
            'content'    => $this->l2_settings['servers'],
            'login_list' => $this->l2_settings['logins'],
        );

        $this->view_data['content'] = $this->load->view('servers/index', $view_data, TRUE);
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

        $this->clear_cache($id);
        
        $this->session->set_flashdata('message', Message::true('Сервер удален'));
        redirect('backend/' . $this->_view);
    }

    public function add()
    {
        // Save
        if(isset($_POST['submit']))
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_error_delimiters('', '');
            
            if($this->form_validation->run('backend_servers'))
            {
                $data_db = elements($this->{$this->_model}->get_fields(), $this->input->post(), NULL);
                
                if($this->{$this->_model}->add($data_db))
                {
                    $this->clear_cache();

                    $message = Message::true('Сервер добавлен');
                }
                else
                {
                    $message = Message::false('Ошибка! Не удалось записать данные в БД');
                }
            }
        }


        $view_data = array(
            'message'    => isset($message) ? $message : '',
            'login_list' => $this->get_all_logins_for_select(),
        );

        $this->view_data['content'] = $this->load->view('servers/add', $view_data, TRUE);
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
            
            if($this->form_validation->run('backend_servers'))
            {
                $data_db = elements($this->{$this->_model}->get_fields(), $this->input->post(), NULL);
                
                if($this->{$this->_model}->edit($data_db, $data_db_where))
                {
                    $this->l2_settings['servers'][$id] = array_merge($this->l2_settings['servers'][$id], $data_db);

                    $message = Message::true('Изменения сохранены');

                    $this->clear_cache($id);
                }
                else
                {
                    $message = Message::false('Ошибка! Не удалось записать данные в БД');
                }
            }
        }
        
        
        $view_data = array(
            'message'    => isset($message) ? $message : '',
            'login_list' => $this->get_all_logins_for_select(),
            'content'    => $this->l2_settings['servers'][$id],
        );

        if(!$view_data['content'])
        {
            redirect('backend/' . $this->_view);
        }



        $this->view_data['content'] = $this->load->view('servers/edit', $view_data, TRUE);
    }
    
    public function _check_login_by_id()
    {
        if(!array_key_exists($this->input->post('login_id'), $this->l2_settings['logins']))
        {
            $this->form_validation->set_message('_check_login_id', 'Логин не существует');
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
        
        $this->clear_cache($id);

        $msg = ($allow == 1 ? 'вкл' : 'выкл');
        $this->session->set_flashdata('message', Message::true($msg));
        redirect('backend/' . $this->_view);
    }

    private function clear_cache($id = '')
    {
        $this->cache->delete('logins');
        $this->cache->delete('servers');
        $this->cache->delete('server_status');
        $this->cache->delete('top_pvp');
        $this->cache->delete('top_pk');
        $this->cache->delete('stats/*' . $id);
    }
}