<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Gallery extends Controllers_Backend_Base
{
    public function __construct()
    {
        parent::__construct();
        
        $class = strtolower(__CLASS__);
        $this->_model = $class . '_model';
        $this->_view  = $class;  
        
        $this->load->model($this->_model);
        
        //$this->_data['field_data'] = $this->{$this->_model}->get_fields();
    }
    
    public function index()
    {
        $view_data = array(
            'gallery_content' => $this->gallery_model->get_list(),
        );

        $this->view_data['content'] = $this->load->view('gallery/index', $view_data, TRUE);
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
            
            if($this->form_validation->run('backend_galery'))
            {
                $img = $_POST['old_img'];

                if(isset($_FILES['img']) && $_FILES['img']['size'] > 0)
                {
                    $this->load->library('upload');

                    $config = array(
                        'upload_path'   => FCPATH . $this->config->item('gallery_path'),
                        'allowed_types' => 'gif|jpg|jpeg|png',
                        'encrypt_name'  => true,
                    );

                    $this->upload->initialize($config);

                    if($this->upload->do_upload('img'))
                    {
                        $data = $this->upload->data();

                        $this->load->library('image_lib');

                        $config = array(
                            'source_image' => $data['full_path'],
                            'width'        => 800,
                            'height'       => 800,
                        );

                        $this->image_lib->initialize($config);

                        // Режу на 800x800
                        if(!$this->image_lib->resize())
                        {
                            $message = msg($this->image_lib->display_errors(), 'false');
                        }

                        // Режу на 100x100
                        if(!$this->upload->display_errors())
                        {
                            $config['width']     = 100;
                            $config['height']    = 100;
                            $config['new_image'] = $data['raw_name'] . '_thumb' . $data['file_ext'];

                            $this->image_lib->initialize($config);

                            if(!$this->image_lib->resize())
                            {
                                $message = msg($this->image_lib->display_errors(), 'false');
                            }
                        }

                        // Удаляю старую фотку
                        if(file_exists(FCPATH . $this->config->item('gallery_path') . '/' . $img))
                        {
                            unlink(FCPATH . $this->config->item('gallery_path') . '/' . $img);
                            unlink(FCPATH . $this->config->item('gallery_path') . '/' . get_thumb($img));
                        }

                        $img = $data['file_name'];
                    }
                    else
                    {
                        $message = msg($this->upload->display_errors(), 'false');
                    }
                }

                if(!$this->upload->display_errors())
                {
                    $data_db        = elements($this->{$this->_model}->get_fields(), $this->input->post(), NULL);
                    $data_db['img'] = $img;

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
        }


        $view_data = array(
            'message' => isset($message) ? $message : '',
            'content' => $this->{$this->_model}->get_row($data_db_where),
        );

        $this->view_data['content'] = $this->load->view('gallery/edit', $view_data, TRUE);
    }
    
    public function add()
    {
        if(isset($_POST['submit']))
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_error_delimiters('', '');
            
            if($this->form_validation->run('backend_galery'))
            {
                if(isset($_FILES['img']) && $_FILES['img']['size'] > 0)
                {
                    $this->load->library('upload');

                    $config = array(
                        'upload_path'   => FCPATH . $this->config->item('gallery_path'),
                        'allowed_types' => 'gif|jpg|jpeg|png',
                        'encrypt_name'  => TRUE,
                    );

                    $this->upload->initialize($config);

                    if($this->upload->do_upload('img'))
                    {
                        $data = $this->upload->data();

                        $this->load->library('image_lib');

                        $config = array(
                            'source_image' => $data['full_path'],
                            'width'        => (int) $this->config->item('gallery_max_width'),
                            'height'       => (int) $this->config->item('gallery_max_height'),
                        );

                        $this->image_lib->initialize($config);

                        // Режу на 800x800
                        if(!$this->image_lib->resize())
                        {
                            $message = msg($this->image_lib->display_errors(), 'false');
                        }

                        // Режу на 100x100
                        if(!$this->upload->display_errors())
                        {
                            $config['width']     = (int) $this->config->item('gallery_min_width');
                            $config['height']    = (int) $this->config->item('gallery_min_height');
                            $config['new_image'] = $data['raw_name'] . '_thumb' . $data['file_ext'];

                            $this->image_lib->initialize($config);

                            if(!$this->image_lib->resize())
                            {
                                $message = msg($this->image_lib->display_errors(), 'false');
                            }
                        }

                        if(!$this->upload->display_errors())
                        {
                            $data_db        = elements($this->{$this->_model}->get_fields(), $this->input->post(), NULL);
                            $data_db['img'] = $data['file_name'];

                            if($this->{$this->_model}->add($data_db))
                            {
                                $message = Message::true('Картинка сохранена');
                            }
                            else
                            {
                                $message = Message::false('Ошибка! Не удалось записать данные в БД');
                            }
                        }
                    }
                    else
                    {
                        $message = Message::false($this->upload->display_errors());
                    }
                }
                else
                {
                    $message = Message::false('Выберите картинку');
                }
            }
        }


        $view_data = array(
            'message' => isset($message) ? $message : '',
        );

        $this->view_data['content'] = $this->load->view('gallery/add', $view_data, TRUE);
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

        $data = $this->{$this->_model}->get_row($data_db_where);

        if($data)
        {
            $this->{$this->_model}->del($data_db_where, 1);

            if(file_exists(FCPATH . $this->config->item('gallery_path') . '/' . $data['img']))
            {
                unlink(FCPATH . $this->config->item('gallery_path') . '/' . $data['img']);
                unlink(FCPATH . $this->config->item('gallery_path') . '/' . get_thumb($data['img']));
            }
        }

        $this->session->set_flashdata('message', Message::true('Картинка удалена'));
        redirect('backend/' . $this->_view);
    }
}