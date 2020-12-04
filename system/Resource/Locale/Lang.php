<?php

namespace Voyager\Resource\Locale;

use Voyager\Facade\Cache;
use Voyager\Facade\File;
use Voyager\Facade\Str;
use Voyager\Util\Arr;
use Voyager\Util\File\Directory;

class Lang extends Translations
{
    /**
     * Store locale data.
     * 
     * @var \Voyager\Util\Arr
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

        if(Str::has($id, '@') && File::exist('resource/locale/' . $break[1] . '.php'))
        {
            $this->id = $break[0];
            $this->filename = $break[1];
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
        $cache = Cache::get('locale');

        if(!is_null(static::$cache) && is_null($cache))
        {
            $cache = static::$cache->get();
        }

        if(is_null($cache))
        {
            $dir = new Directory('resource/locale/');
            $data = new Arr();

            if($dir->exist())
            {
                foreach($dir->files() as $file)
                {
                    if($file->is('php'))
                    {
                        $file->require();

                        if(!Locale::empty())
                        {
                            $locales = new Arr();

                            foreach(Locale::get() as $locale)
                            {
                                $locales->set($locale->getId(), $locale->data());
                            }

                            $data->set($file->name(), $locales->get());
                            Locale::clear();
                        }
                    }
                }

                static::$cache = $data->get();

                Cache::store('locale', $data->get());
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
     * @return  string
     */

    public function get(string $lang = 'en', array $replace = [])
    {
        $id = $this->id;
        $cache = static::$cache;
        $filename = $this->filename;
        $replace = new Arr($replace);
        $response = $id;

        if(is_null($filename))
        {
            $locales = new Arr();

            foreach($cache->get() as $locale)
            {
                foreach($locale as $key => $trans)
                {
                    $locales->set($key, $trans);
                }
            }

            if($locales->hasKey($id))
            {
                $locale = new Arr($locales->get($id));
                
                if($locale->hasKey($lang))
                {
                    $response = $locale->get($lang);
                }
            }
        }
        else
        {
            if($cache->hasKey($filename))
            {
                $locales = new Arr($cache->get($filename));

                if($locales->hasKey($id))
                {
                    $locale = new Arr($locales->get($id));

                    if($locale->hasKey($lang))
                    {
                        $response = $locale->get($lang);
                    }
                }
            }
        }

        if(!$replace->empty())
        {
            return $this->replaceTemplates($response, $replace);
        }
        else
        {
            return $response;
        }
    }

    /**
     * Find and replace templates from string.
     * 
     * @param   string $string
     * @return  string
     */

    private function replaceTemplates(string $string, Arr $replace)
    {
        if(!$replace->empty())
        {
            foreach($replace->get() as $key => $value)
            {
                $string = str_replace('{' . $key . '}', $value, $string);
            }
        }

        return $string;
    }

}