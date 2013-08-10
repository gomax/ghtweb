<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Users extends Controllers_Backend_Base
{
    /**
     * @var array: Названия полей которые будут браться из $_GET для поиска
     */
    private $_search_fields = array('login', 'email', 'last_ip');
    
    
    
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
        $limit = (int) $this->config->item('users_per_page', 'backend');
        
        
        $data_db_like = array();
        
        // Поиск
        if($this->input->get())
        {
            $get = $this->input->get();
            
            foreach($get as $key => $val)
            {
                $val = trim($val);
                
                if(in_array($key, $this->_search_fields) && $val != '')
                {
                    $data_db_like[$key] = $val;
                }
            }
        }

        $count = $this->{$this->_model}->get_count(NULL, $data_db_like);

        // Пагинация
        $this->load->library('pagination');

        Pagination::initialize(array(
            'base_url'   => '/backend/users/',
            'total_rows' => $count,
            'per_page'   => $limit,
        ));

        $view_data = array(
            'pagination' => Pagination::create_links(),
            'content'    => $this->{$this->_model}->get_list(Pagination::$offset, $limit, NULL, 'created_at', 'DESC', $data_db_like),
            'count'      => $count,
            'offset'     => Pagination::$offset,
        );

        $this->view_data['content'] = $this->load->view('users/index', $view_data, TRUE);
    }

    // @TODO, удаление
    public function del() {}
    
    public function edit()
    {
        $user_id = (int) $this->uri->segment(4);
        
        if($user_id < 1)
        {
            redirect('backend/' . $this->_view);
        }
        
        // Save
        if(isset($_POST['submit']))
        {
            $this->load->library('form_validation');

            $this->form_validation->set_error_delimiters('', '<br />');
            
            $this->form_validation->set_rules('password', 'Пароль', 'trim|min_length[4]');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback__check_email');
            $this->form_validation->set_rules('protected_ip', 'Защита аккаунта по IP', 'trim|callback__check_valid_ip');
            $this->form_validation->set_rules('group', 'Группа', 'trim|required|integer|callback__check_group');


            if($this->form_validation->run())
            {
                $data_db = elements($this->{$this->_model}->get_fields(), $this->input->post(), NULL);
                
                if($this->input->post('password'))
                {
                    $data_db['password'] = $this->auth->password_encript($this->input->post('password', true));
                }
                else
                {
                    $data_db['password'] = $this->input->post('old_password', true);
                }
                
                // Сбрасываю хэш, чтобы юзера выкинуло
                $data_db['cookie_hash'] = NULL;
                
                $data_db_where = array(
                    'user_id' => $user_id,
                );
                
                if($this->{$this->_model}->edit($data_db, $data_db_where))
                {
                    $message = Message::true('Данные сохранены');
                }
                else
                {
                    $message = Message::false('Ошибка! Не удалось записать данные в БД');
                }
            }
        }


        $view_data = array(
            'content'     => $this->{$this->_model}->get_row(array('user_id' => $user_id)),
            'message'     => isset($message) ? $message : '',
            'user_groups' => $this->get_user_groups(),
        );

        $this->view_data['content'] = $this->load->view('users/edit', $view_data, TRUE);
    }

    private function get_user_groups()
    {
        $this->load->model('user_groups_model');

        $res = $this->user_groups_model->get_list();
        $return = array();

        foreach($res as $row)
        {
            $return[$row['id']] = $row['group_name'];
        }

        return $return;
    }
    
    public function add()
    {
        $this->_data['groups'] = $this->user_groups_model->get_groups_names();
        
        // Save
        if(isset($_POST['submit']))
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_error_delimiters('', '');
            
            $this->form_validation->set_rules('login', 'Логин', 'trim|required|xss_clean|min_length[4]|max_length[20]|callback__check_user_login');
            $this->form_validation->set_rules('password', 'Пароль', 'trim|required|min_length[4]|max_length[20]');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback__check_user_email');
            $this->form_validation->set_rules('group', 'Группа', 'trim|required|integer|callback__check_user_group');


            if($this->form_validation->run())
            {
                $this->load->helper('string');
                
                $data_db              = elements($this->{$this->_model}->get_fields(), $this->input->post(), NULL);
                $data_db['password']  = $this->auth->password_encript($data_db['password']);
                $data_db['activated'] = 1;
                $data_db['login']     = $this->input->post('login', true);
                
                if($this->{$this->_model}->add($data_db))
                {
                    $message = Message::true('Пользователь добавлен');
                }
                else
                {
                    $message = Message::false('Ошибка! Не удалось записать данные в БД');
                }
            }
        }


        $view_data = array(
            'message'     => isset($message) ? $message : '',
            'user_groups' => $this->get_user_groups(),
        );

        $this->view_data['content'] = $this->load->view('users/add', $view_data, TRUE);
    }
    
    /**
     * Активация аккаунта
     * 
     * @param integer $user_id
     */
    public function activated()
    {
        $user_id = (int) $this->uri->segment(4);
        
        if($user_id < 1)
        {
            $this->session->set_flashdata('message', Message::false('Не выбран пользователь'));
            redirect('backend/users');
        }
        
        $data_db = array(
            'activated'           => '1',
            'activated_hash'      => NULL,
            'activated_hash_time' => NULL,
        );
        
        $data_db_where = array(
            'user_id' => $user_id,
        );
        
        if($this->users_model->edit($data_db, $data_db_where))
        {
            $this->session->set_flashdata('message', Message::true('Аккаунт активирован'));
        }
        else
        {
            $this->session->set_flashdata('message', Message::false('Ошибка! Обратитесь к Администрации сайта'));
        }
        
        redirect('backend/users');
    }
    
    /**
     * Бан/Разбан пользователя
     * 
     * @param integer $user_id
     * @param string $type
     * @param string $banned_reason
     */
    public function banned()
    {
        $user_id       = (int) $this->uri->segment(4);
        $type          = $this->uri->segment(5);
        $banned_reason = $this->input->post('banned_reason', true);
        
        
        if($user_id < 1)
        {
            $this->session->set_flashdata('message', Message::false('Не выбран пользователь'));
            redirect('backend/users');
        }

        $banned_reason = ($banned_reason == '' ? NULL : $banned_reason);

        $data_db = array(
            'banned'        => '1',
            'banned_reason' => $banned_reason,
            'cookie_hash'   => NULL,
        );
        
        $data_db_where = array(
            'user_id' => $user_id,
        );

        if($type == 'off')
        {
            $data_db['banned'] = '0';
            $data_db['banned_reason'] = NULL;
        }
        
        
        if($this->users_model->edit($data_db, $data_db_where))
        {
            $msg = 'Пользователь забанен';
            
            if($type == 'off')
            {
                $msg = 'Пользователь разбанен';
            }
            
            $this->session->set_flashdata('message', Message::true($msg));
        }
        else
        {
            $this->session->set_flashdata('message', Message::false('Ошибка! Обратитесь к Администрации сайта'));
        }
        
        redirect('backend/users');
    }
}