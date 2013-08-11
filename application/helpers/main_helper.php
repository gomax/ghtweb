<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if(!function_exists('prt'))
{
    function prt($a)
    {
        echo '<pre style="padding: 0 3px 2px;font-size: 12px;color: #333333;-webkit-border-radius: 3px;-moz-border-radius: 3px;border-radius: 3px;display: block;padding: 8.5px;margin: 0 0 9px;font-size: 12.025px;line-height: 18px;word-break: break-all;word-wrap: break-word;white-space: pre;white-space: pre-wrap;background-color: #f5f5f5;border: 1px solid #ccc;border: 1px solid rgba(0, 0, 0, 0.15);-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;">';
        print_r($a);
        echo '</pre>';
    }
}

if(!function_exists('redirect_back'))
{
    /**
     * Редирект на предыдущий URL
     * 
     * @param string: Если REFERER был не с текущего сайта то редирект будет на $other_url
     */
    function redirect_back($other_url = '')
    {
        if(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) !== false)
        {
            redirect($_SERVER['HTTP_REFERER']);
        }
        
        redirect($other_url);
    }
}

/**
 * array(
 *     'links' => array(
 *         array('name' => 'Название ссылки', 'url' => 'ссылка', 'title' => 'титул ссылки', 'current' => true);
 *         array('name' => 'Название ссылки', 'url' => 'ссылка', 'title' => 'титул ссылки');
 *     ),
 *     'delimiter' => '/',
 */
if(!function_exists('breadcrumb'))
{
    function breadcrumb(array $data = NULL)
    {
        // Разделитель
        $delimiter = (isset($data['delimiter']) ? $data['delimiter'] : '/');
        
        $html = '<ul class="breadcrumb">';
        
        $count = count($data['links']);
        
        foreach($data['links'] as $key => $options)
        {
            $current = '';
            
            if(!empty($options['current']) && $options['current'] === true)
            {
                $current = ' class="active"';
            }
            
            $title = (!empty($options['title']) ? 'title="' . $options['title'] . '"' : '');
            
            $url = (isset($options['url']) ? anchor($options['url'], $options['name'], $title) : $options['name']);
            
            $divider = (($key+1) != $count ? '<span class="divider">' . $delimiter . '</span>' : '');
            
            $html .= '<li' . $current . '>' . $url . $divider . '</li>';
        }
        
        $html .= '</ul>';
        
        return $html;
    }
}

if(!function_exists('db_date'))
{
    function db_date($time = '')
    {
        return date('Y-m-d H:i:s', ($time == '' ? time() : $time));
    }
}

if(!function_exists('send_mail'))
{
    function send_mail($to, $title, $message)
    {
        $CI =& get_instance();

        $config['protocol']     = 'mail';
        $config['mailtype']     = 'html';
        $config['smtp_timeout'] = 30;
        $config['newline']      = "\r\n"; 

        if($CI->config->item('mail_method') == 'smtp')
        {
            $config['protocol']  = 'smtp';
            $config['smtp_host'] = $CI->config->item('mail_smtp_host');
            $config['smtp_user'] = $CI->config->item('mail_smtp_user');
            $config['smtp_pass'] = $CI->config->item('mail_smtp_pass');
            $config['smtp_port'] = $CI->config->item('mail_smtp_port');
        }
        
        $CI->load->library('email', $config);
               
        $CI->email->from($CI->config->item('mail_site_email'), $CI->config->item('mail_site_author'));
        $CI->email->to($to);
        $CI->email->subject($title);
        $CI->email->message($message);
        
        return $CI->email->send();
    }
}

if(!function_exists('uri_segment'))
{
    function uri_segment($number = 1)
    {
        $CI =& get_instance();
        
        $fsu = $CI->uri->segment(1);
        
        if($CI->config->item($fsu, 'languages'))
        {
            $number++;
        }
        
        return $number;
    }
}

/**
 * Аналог $this->uri->segment()
 *
 * @param $number
 *
 * @return string
 */
function us($number)
{
    $CI =& get_instance();
    return $CI->uri->segment($number);
}

if(!function_exists('get_templates'))
{
    function get_templates()
    {
        $tpl_dir = FCPATH . 'templates';
        
        $scan = scandir($tpl_dir);
        $res = array();
        
        foreach($scan as $k => $v)
        {
            if($v != '.' && $v != '..' && $v != 'backend')
            {
                $res[$v] = $v;
            }
        }
        
        return $res;
    }
}

function tinymce(array $fields)
{
    $fields = implode(',#', $fields);

    echo '
    <script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: "#' . $fields . '",
            language_url: "' . base_url() . 'resources/libs/tinymce/langs/ru.js",
            plugins: [
                "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "table contextmenu directionality emoticons template textcolor paste textcolor"
            ],

            toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
            toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | inserttime preview | forecolor backcolor",
            toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",

            toolbar_items_size: "small"
        });
    </script>';
}

if(!function_exists('get_thumb'))
{
    function get_thumb($img)
    {
        $ext = preg_replace('/^.*\./', '', $img);
        return str_replace('.' . $ext, '_thumb.' . $ext, $img);
    }
}

if(!function_exists('get_margin_top'))
{
    function get_margin_top($img, $heigth)
    {
        $CI =& get_instance();

        $big_img_path = FCPATH . $CI->config->item('gallery_path') . '/' . $img;
        $small_img_path = FCPATH . $CI->config->item('gallery_path') . '/' . get_thumb($img);

        if(file_exists($big_img_path))
        {
            $img_data = getimagesize($small_img_path);
            return round(($heigth - $img_data[1]) / 2);
        }

        return 0;
    }
}

if(!function_exists('e'))
{
    function e($value)
    {
        return htmlentities($value, ENT_QUOTES, config_item('charset'), FALSE);
    }
}

function log_file($text, $file)
{
    $path = APPPATH . 'logs/' . $file . '.php';

    $msg = "-------------------------------- [ " . db_date() . " ] --------------------------------\n";
    $msg .= $text;
    $msg .= "\n-----------------------------------------------------------------------------------------\n\n";

    if(!is_file($path))
    {
        $msg = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>\n\n" . $msg;
        return file_put_contents($path, $msg);
    }

    return file_put_contents($path, $msg, FILE_APPEND);
}

/**
 * Возвращает префиксы, используется при регистрации игрового аккаунта
 *
 * @return array|bool
 */
function get_game_account_prefixes()
{
    $CI =& get_instance();

    if($CI->config->item('game_account_prefix_allow') != 1 || $CI->config->item('game_account_prefix_count') < 1)
    {
        return FALSE;
    }

    $CI->load->helper('string');

    $prefixes = array();

    for($i = 0; $i < $CI->config->item('game_account_prefix_count'); $i++)
    {
        $str = random_string($CI->config->item('game_account_prefix_type'), $CI->config->item('game_account_prefix_length'));
        $prefixes[$str] = $str;
    }

    return $prefixes;
}
