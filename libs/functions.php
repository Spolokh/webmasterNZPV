<?php


class functions
{
    public function __construct()
    {
        echo __FILE__ ;
    }
}

function AutoLoader($class)
{
    $file = 'libs' .DIRECTORY_SEPARATOR. str_replace('\\', '/', $class).'.php';
        
    if ( file_exists($file) )
    {
        require_once $file;
        
        if (class_exists($class)) 
        {
            return TRUE;
        }
    }
    return FALSE;
}
