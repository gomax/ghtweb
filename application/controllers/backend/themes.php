<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Themes extends Controllers_Backend_Base
{
    public function index()
    {
        if($content = $this->get_server_themes($this->config->item('themes_url', 'backend') . '/get_themes'))
        {
            $content = json_decode($content);
        }

        $view_data = array(
            'content' => $content,
        );

        $this->view_data['content'] = $this->load->view('themes/index', $view_data, TRUE);
    }

    public function install()
    {
        $theme_id = (int) $this->uri->segment(4);

        if($theme_id < 1)
        {
            redirect('backend/themes');
        }


        if($theme_data = $this->get_server_themes($this->config->item('themes_url', 'backend') . '/get_theme?theme_id=' . $theme_id))
        {
            $theme_data = json_decode($theme_data);

            if(is_object($theme_data))
            {
                $error = FALSE;
                $path  = FCPATH . 'templates/' . $theme_data->slug;

                // Проверка папки
                if(is_dir($path))
                {
                    $error = TRUE;
                    $message = Message::false('Папка: ' . $theme_data->slug . ' уже существует');
                }

                // Создание папки
                if(!$error && !mkdir($path, 0777, TRUE))
                {
                    $error = TRUE;
                    $message = Message::false('Не удалось создать папку: ' . $theme_data->slug);
                }

                // Копирую шаблон с сервера
                $new_zip_dir = $path . '/' . $theme_data->slug . '.zip';

                if(!$error && !copy($theme_data->file_name, $new_zip_dir))
                {
                    $error = TRUE;
                    $message = Message::false('Не удалось скопировать с сервера шаблон');
                }

                // Распаковка шаблона
                if(!$error && !$this->unzip($new_zip_dir, $path . '/'))
                {
                    $error = TRUE;
                    $message = Message::false('Не удалось распаковать шаблон');
                }

                // Удаляю мусор
                if(!$error)
                {
                    unlink($new_zip_dir);
                    $message = Message::true('Шаблон установлен');
                }
            }
            else
            {
                $message = Message::false('Не удалось забрать шаблон с сервера');
            }
        }
        else
        {
            $message = Message::false('Сервер не найден');
        }


        $this->view_data['message'] = $message;

        $this->view_data['content'] = $this->load->view('themes/install', $this->view_data, TRUE);
    }

    private function unzip($file, $dir)
    {
        if(!file_exists($dir))
        {
            mkdir($dir,0777);
        }

        $zip_handle = zip_open($file);

        if(is_resource($zip_handle))
        {
            while($zip_entry = zip_read($zip_handle))
            {
                if($zip_entry)
                {
                    $zip_name = zip_entry_name($zip_entry);
                    $zip_size = zip_entry_filesize($zip_entry);

                    if(($zip_size == 0) && ($zip_name[strlen($zip_name) - 1] == '/'))
                    {
                        mkdir($dir . $zip_name, 0775);
                    }
                    else
                    {
                        @zip_entry_open($zip_handle, $zip_entry, 'r');
                        $fp = @fopen($dir . $zip_name,'wb+');
                        @fwrite($fp, zip_entry_read($zip_entry, $zip_size),$zip_size);
                        @fclose($fp);
                        @chmod($dir.$zip_name, 0775);
                        @zip_entry_close($zip_entry);
                    }
                }
            }

            return TRUE;
        }
        else
        {
            zip_close($zip_handle);
            return FALSE;
        }
    }

    private function get_server_themes($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}