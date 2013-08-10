<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pagination
{
    private static $_params = array(
        'base_url'             => '',
        'is_ajax'              => FALSE,
        'total_rows'           => 0,
        'per_page'             => 10,
        'num_links'            => 5,
        'query_string_segment' => 'page',
    );

    public static $offset = 0;



    public static function initialize(array $params = NULL)
    {
        if($params != NULL)
        {
            self::$_params = array_merge(self::$_params, $params);
        }
    }

    public static function create_links()
    {
        $output = '';

        if(self::$_params['total_rows'] < 1)
        {
            return $output;
        }

        $page = filter_input(INPUT_GET, self::$_params['query_string_segment'], FILTER_VALIDATE_INT);

        if(self::$_params['is_ajax'] === TRUE)
        {
            $page = filter_input(INPUT_POST, self::$_params['query_string_segment'], FILTER_VALIDATE_INT);
        }

        if($page === NULL)
        {
            $page = 0;
        }

        $count_pages = ceil(self::$_params['total_rows'] / self::$_params['per_page']);

        // $_GET
        $_gets = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);

        $url = '';

        if(is_array($_gets))
        {
            if(isset($_gets[self::$_params['query_string_segment']]))
            {
                unset($_gets[self::$_params['query_string_segment']]);
            }

            $url = array();

            foreach($_gets as $key => $val)
            {
                $url[] = $key . '=' . $val;
            }

            $url = ($url ? '?' . implode('&', $url) : '');
        }

        $postfix = ($url == '' ? '?' : '&') . self::$_params['query_string_segment'] . '=';
        $url     = self::$_params['base_url'] . $url;


        $output = '<div class="pagination"><ul>';


        // Первая ссылка
        if($page > 5)
        {
            $output .= '<li class="first"><a href="' . $url . '">first</a></li>';
        }

        // Предыдущая ссылка
        if($page > 1)
        {
            if($page == 2)
            {
                $output .= '<li class="first"><a href="' . $url . '">prev</a></li>';
            }
            else
            {
                $output .= '<li class="first"><a href="' . $url . $postfix . ($page - 1) . '">prev</a></li>';
            }
        }

        $start = ($page - self::$_params['num_links'] > 0 ? $page - self::$_params['num_links'] : 1);
        $stop  = ($page + self::$_params['num_links'] > $count_pages ? $count_pages : $page + self::$_params['num_links']);


        for($i = $start; $i <= $stop; $i++)
        {
            $href   = '';
            $active = '';

            if($i == $page)
            {
                $active = ' class="active"';
            }
            elseif($page == 0 && $i == 1)
            {
                $active = ' class="active"';
            }
            else
            {
                if($i == 1)
                {
                    $href = ' href="' . $url . '"';
                }
                else
                {
                    $href = ' href="' . $url . $postfix . $i . '"';
                }
            }

            $output .= '<li' . $active . '><a' . $href . '>' . $i . '</a></li>';
        }

        // Следующая ссылка
        if($page < $count_pages)
        {
            $output .= '<li class="next"><a href="' . $url . $postfix . (($page == 0 ? $page + 1 : $page) + 1) . '">next</a></li>';
        }

        // Последняя ссылка
        if($page + 5 <= $count_pages)
        {
            $output .= '<li class="last"><a href="' . $url . $postfix . $count_pages . '">last</a></li>';
        }

        $output .= '</ul></div>';

        self::$offset = (self::$_params['per_page'] * $page - self::$_params['per_page']);

        if(self::$offset < 0)
        {
            self::$offset = 0;
        }

        return $count_pages > 1 ? $output : '';
    }
}
