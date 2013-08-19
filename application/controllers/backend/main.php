<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Main extends Controllers_Backend_Base
{
    private $_cache_size = 0;



	public function index()
	{
        $view_data = array();

        // Размер БД
        $query = $this->db->query('SHOW TABLE STATUS');
        
        $length_bd = 0;
        
        if(is_object($query))
        {
            $query = $query->result_array();
            
            foreach($query as $row)
            {
                $length_bd += $row['Data_length'];
            }
        }
        
        $this->load->helper('number');
        
        $view_data['length_bd'] = byte_format($length_bd);
        
        unset($query, $length_bd);        
        
        
        // Размер кэша
        $this->get_cache_size(FCPATH . 'application/cache');
        $view_data['cache_size'] = byte_format($this->_cache_size);
        

        // Кол-во регистраций
        $view_data['users_count_register'] = $this->db->count_all('users');
        
        // Кол-во регистраций за последнии 30 дней
        $this->db->where('UNIX_TIMESTAMP(created_at) > UNIX_TIMESTAMP(NOW()) - 2592000');
        $view_data['users_count_register_last_30_dey'] = $this->db->count_all_results('users');
        
        // Не активированных регистраций
        $this->db->where('activated', '0');
        $view_data['users_count_not_activated'] = $this->db->count_all_results('users');
        
        // Кол-во регистраций за последнии 7 дней
        $this->db->where('UNIX_TIMESTAMP(created_at) > UNIX_TIMESTAMP(NOW()) - 604800');
        $view_data['users_count_register_last_7_day'] = $this->db->count_all_results('users');
        
         // Забаненые
        $this->db->where('banned', '1');
        $view_data['users_count_banned'] = $this->db->count_all_results('users');

        
        
        // Данные для графика по регистрациям
        $this->graph_data();

        $this->view_data['content'] = $this->load->view('main/index', $view_data, TRUE);
	}
    
    private function graph_data()
    {
        $data = $this->db->select('created_at as date, COUNT(0) as count')
                         ->group_by('DAY(created_at)')
                         ->order_by('created_at')
                         ->get('users')
                         ->result_array();
        
        $time = array();
        $count = array();
        
        foreach($data as $row)
        {
            $time[]  = substr($row['date'], 0, 10);
            $count[] = $row['count'];
        }
        
        $this->view_data['reg_data_time'] = "['" . join("','", $time) . "']";
        $this->view_data['reg_data_count'] = "[" . join(",", $count) . "]";
    }

    public function get_cache_size($dir)
    {
        $directory = scandir($dir);
        $count     = count($directory);

        for($i = 0; $i < $count; $i++)
        {
            if($i < 2)
            {
                continue;
            }

            if(is_dir($dir . '/' . $directory[$i]))
            {
                $this->get_cache_size($dir . '/' . $directory[$i]);
            }
            elseif(is_file($dir . '/' . $directory[$i]))
            {
                if($directory[$i] != 'index.html' && $directory[$i] != '.htaccess')
                {
                    $this->_cache_size += filesize($dir . '/' . $directory[$i]);
                }
            }
        }
    }

    public function ajax_clear_cache()
    {
        if(!$this->input->is_ajax_request())
        {
            redirect();
        }

        $this->cache->clean();
    }
}