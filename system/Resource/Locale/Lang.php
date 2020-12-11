<?php

namespace Voyager\Resource\Locale;

use Voyager\Facade\Str;
use Voyager\Util\Arr;
use Voyager\Util\File\Directory;
use Voyager\Util\File\Reader;

class Lang extends Translations
{
    /**
     * Store locale data.
     * 
     * @var array
     */

    private static $cache;

    /**
     * Store locale filename.
     * 
     * @var string
     */

    private $filename;

    /**
     * Store lang id.
     * 
     * @var string
     */

    private $id;

    /**
     * Create new lang instance.
     * 
     * @param   string $id
     * @return  void
     */

    public function __construct(string $id)
    {
        $break = Str::break($id, '@');
        
        if(Str::has($id, '@'))
        {
            $reader = new Reader('resource/locale/' . $break[1] . '.php');

            if($reader->exist())
            {
                $this->id = $break[0];
                $this->filename = $break[1];
            }
            else
            {
                $this->id = $break[0];
            }
        }
        else
        {
            $this->id = $id;
        }

        if(is_null(static::$cache))
        {
            static::$cache = static::load();
        }
    }

    /**
     * Load all translations from resource.
     * 
     * @return  \Voyager\Util\Arr
     */

    public static function load()
    {
        $cache = cache('locale');

        if(!is_null(static::$cache) && is_null($cache))
        {
            $cache = static::$cache;
        }

        if(is_null($cache))
        {
            $dir = new Directory('resource/locale/');
            $data = [];

            if($dir->exist())
            {
                foreach($dir->files() as $file)
                {
                    if($file->is('php'))
                    {
                        $file->require();

                        if(!Locale::empty())
                        {
                            $locales = [];

                            foreach(Locale::get() as $locale)
                            {
                                $locales[$locale->getId()] = $locale;
                            }

                            $data[$file->name()] = $locales;
                            Locale::clear();
                        }
                    }
                }

                static::$cache = $data;
                cache('locale', $data);
            }
            
            return $data;
        }
        else
        {
            return new Arr($cache);
        }
    }

    /**
     * Return localize translation of key.
     * 
     * @param   string $lang
     * @param   array $replace
     * @return  string
     */

    public function get(string $lang = 'en', array $replace = [])
    {
        $id = $this->id;
        $backup = app()->backup_locale;
        $response = $this->getTranslation($id, $lang);

        if($response === $id && !is_null($backup))
        {
            $get = $this->getTranslation($id, $backup);

            if($get !== $id)
            {
                $response = $get;
            }
        }

        if(!empty($replace) && $response !== $id)
        {
            return $this->replaceTemplates($response, $replace);
        }
        else
        {
            return $response;
        }
    }

    /**
     * Return language translations.
     * 
     * @param   string $id
     * @param   string $lang
     * @return  string
     */

    private function getTranslation(string $id, string $lang)
    {
        $cache = static::$cache;
        $filename = $this->filename;
        $response = $id;

        if(is_null($filename))
        {
            $locales = [];

            foreach($cache as $locale)
            {
                foreach($locale as $key => $trans)
                {
                    $locales[$key] = $trans;
                }
            }

            if(array_key_exists($id, $locales))
            {
                $locale = $locales[$id]->data();
                
                if(array_key_exists($lang, $locale))
                {
                    $response = $locale[$lang];
                }
            }
        }
        else
        {
            if(array_key_exists($filename, $cache))
            {
                $locales = $cache[$filename];

                if(array_key_exists($id, $locales))
                {
                    $locale = $locales[$id]->data();

                    if(array_key_exists($lang, $locale))
                    {
                        $response = $locale[$lang];
                    }
                }
            }
        }

        return $response;
    }

    /**
     * Find and replace templates from string.
     * 
     * @param   string $string
     * @param   array $replace
     * @return  string
     */

    private function replaceTemplates(string $string, array $replace)
    {
        foreach($replace as $key => $value)
        {
            $string = str_replace('{' . $key . '}', $value, $string);
        }
    
        return $string;
    }

}