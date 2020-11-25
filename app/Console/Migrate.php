<?php

namespace App\Console;

use Voyager\App\Console;
use Voyager\Console\Message;
use Voyager\Database\DB;
use Voyager\Facade\Arr;
use Voyager\Facade\Str;
use Voyager\Util\File\Directory;

class Migrate extends Console
{
    /**
     * Create database tables declared from models.
     * 
     * @return  void
     */

    protected function main()
    {
        $msg = new Message();
        $dir = new Directory('app/Database/Model');
        $tables = DB::tables();
        $models = [];
        $msg->set(sizeof($dir->fileList()) . ' models has been found.');
        $msg->success('Database migration has started...');
        $n = 0;

        foreach($dir->files() as $file)
        {
            $converted = Str::toKebabCase($file->name());
            $models[] = $converted;
        }

        // Drop non-existing tables.

        foreach($tables as $table)
        {
            if(!in_array($table, $models))
            {
                DB::table($table)->drop();
                $msg->set('{red}' . $table . '{/red} has been dropped.');
            }
        }

        // Create new table.
        
        foreach($dir->files() as $model)
        {
            $name = $model->name();
            $model = 'App\Database\Model\\' . $name;
            $alias = Str::toKebabCase($name);

            if(!in_array($alias, $tables))
            {
                $msg->set('{yellow}' . $name . '{/yellow} has started to migrate.');
                $instance = $model::migration();

                if($instance->success())
                {
                    $n++;
                    $msg->set('{yellow}' . $name . '{/yellow} model migration has succeed.');
                }
                else
                {
                    $msg->set('{yellow}' . $name . '{/yellow} model migration has failed.');
                }
            }
            else
            {
                $schema = $model::columns()->get();
                $column1 = DB::table($alias)->columns();
                $column2 = Arr::keys($schema);
                $compare1 = Arr::diff($column1, $column2);
                $compare2 = Arr::diff($column2, $column1);

                if(!empty($compare1))
                {
                    foreach($compare1 as $column)
                    {
                        $msg->error($column . ' column has been dropped.');
                        DB::table($alias)->drop($column);
                    }
                }
                else if(!empty($compare2))
                {
                    foreach($compare2 as $column)
                    {
                        $data = $schema[$column];
                        
                        $msg->success($column . ' column has been added.');
                        DB::query('ALTER TABLE ' . $alias . ' ADD ' . $data->generate());
                    }
                }
                else
                {
                    $msg->set('{yellow}' . $name . '{/yellow} was already migrated.');
                }
            }
        }

        if($n != 0)
        {
            $msg->success('Successfully migrated ' . $n . ' models.');
        }
        else
        {
            $msg->error('No model has been migrated.');
        }
    }

}
