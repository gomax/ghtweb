<?php

function config($item, $index = '')
{
    $CI =& get_instance();
    return $CI->config->item($item, $index);
}
