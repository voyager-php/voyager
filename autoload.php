<?php

if(function_exists('spl_autoload_register'))
{
    spl_autoload_register(function(string $namespace) use($root) {
        
        $basename = substr($namespace, 0, strpos($namespace, '\\'));
        $paths = [
            'App'           => 'app/',
            'Voyager'       => 'system/',
            'Components'    => 'resource/view/component/',
        ];

        if(array_key_exists($basename, $paths))
        {
            $location = $paths[$basename];
            $path = str_replace('\\', '/', substr($namespace, strlen($basename) + 1, strlen($namespace)));
            $file = $root . $location . $path . '.php';
        
            if(file_exists($file) && is_readable($file))
            {
                include($file);
            }
        }
    });
}