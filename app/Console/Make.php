<?php

namespace App\Console;

use Voyager\App\Console;
use Voyager\Console\Message;
use Voyager\Facade\Dir;
use Voyager\Facade\File;
use Voyager\Facade\Str;
use Voyager\Util\Arr;
use Voyager\Util\File\Directory;
use Voyager\Util\File\Reader;
use Voyager\Util\Str as Builder;

class Make extends Console
{
    /**
     * Make new dotenv file.
     * 
     * @param   string $filename
     * @return  void
     */

    protected function env(string $filename)
    {
        $msg = new Message();
        $file = new Reader($filename);

        if(!$file->exist())
        {
            $example = new Reader('.env.example');

            if($example->exist())
            {
                $file->make($example->content());
                $msg->success('DotEnv file was successfully created.');
            }
            else
            {
                $msg->error('Your .env.example file was missing.');
            }
        }
        else
        {
            $msg->error('DotEnv file already exist.');
        }
    }

    /**
     * Create new folder when not existing.
     * 
     * @param   string $path
     * @param   string $name
     * @param   bool $ucfirst
     * @return  mixed
     */

    private function manageFolder(string $path, string $name, bool $ucfirst = true)
    {
        $path = Str::moveFromEnd($path, '/');

        $name = $ucfirst ? ucfirst($name) : $name;
        $msg = new Message();
        $dir = new Directory($path);

        if(!$dir->exist())
        {
            $msg->success($name . ' folder has been created.');
            $split = new Arr(explode('/', $path));
            $split->pop();

            $dir = new Directory($split->implode('/') . '/');

            return $dir->makeFolder($name);
        }

        return $dir;
    }

    /**
     * Return if second parameter exist.
     * 
     * @param   string $keyword
     * @param   array $arg
     * @return  bool
     */

    private function hasSecondParameter(string $keyword, array $arg)
    {
        $output = false;

        if($arg[2] ?? false)
        {
            if($arg[2] === $keyword)
            {
                $output = true;
            }
        }

        return $output;
    }

    /**
     * Make controller class file.
     * 
     * @param   string $filename
     * @return  void
     */

    protected function controller(string $filename, array $arg)
    {
        $dir = $this->manageFolder('app/Controller', 'Controller');
        $classname = ucfirst($filename);
        $filename = $classname . '.php';
        $msg = new Message();
        $res = $this->hasSecondParameter('-r', $arg);
        
        if(!$dir->has($filename))
        {
            $builder = new Builder('<?php');
            $builder->br()
                    ->br()
                    ->append('namespace App\Controller;')
                    ->br()
                    ->br()
                    ->append('use Voyager\App\Controller;')
                    ->br()
                    ->append('use Voyager\App\Request;')
                    ->br()
                    ->br()
                    ->append('class ' . $classname)
                    ->append(' extends Controller')
                    ->br()
                    ->append('{')
                    ->br()
                    ->append('    /**')
                    ->br()
                    ->append('     * Default controller method.')
                    ->br()
                    ->append('     *')
                    ->br()
                    ->append('     * @param \Voyager\App\Request $request')
                    ->br()
                    ->append('     * @return mixed')
                    ->br()
                    ->append('     */')
                    ->br()
                    ->br()
                    ->append('    protected function index(Request $request)')
                    ->br()
                    ->append('    {')
                    ->br()
                    ->append('        return null;')
                    ->br()
                    ->append('    }');

            if($res)
            {
                $builder->br()
                        ->br()
                        ->append('    /**')
                        ->br()
                        ->append('     * Return resource details method.')
                        ->br()
                        ->append('     *')
                        ->br()
                        ->append('     * @param \Voyager\App\Request $request')
                        ->br()
                        ->append('     * @return mixed')
                        ->br()
                        ->append('     */')
                        ->br()
                        ->br()
                        ->append('    protected function show(Request $request)')
                        ->br()
                        ->append('    {')
                        ->br()
                        ->append('        return null;')
                        ->br()
                        ->append('    }')
                        ->br()
                        ->br()
                        ->append('    /**')
                        ->br()
                        ->append('     * Create resource method.')
                        ->br()
                        ->append('     *')
                        ->br()
                        ->append('     * @param \Voyager\App\Request $request')
                        ->br()
                        ->append('     * @return mixed')
                        ->br()
                        ->append('     */')
                        ->br()
                        ->br()
                        ->append('    protected function create(Request $request)')
                        ->br()
                        ->append('    {')
                        ->br()
                        ->append('        return null;')
                        ->br()
                        ->append('    }')
                        ->br()
                        ->br()
                        ->append('    /**')
                        ->br()
                        ->append('     * Edit resource method.')
                        ->br()
                        ->append('     *')
                        ->br()
                        ->append('     * @param \Voyager\App\Request $request')
                        ->br()
                        ->append('     * @return mixed')
                        ->br()
                        ->append('     */')
                        ->br()
                        ->br()
                        ->append('    protected function edit(Request $request)')
                        ->br()
                        ->append('    {')
                        ->br()
                        ->append('        return null;')
                        ->br()
                        ->append('    }')
                        ->br()
                        ->br()
                        ->append('    /**')
                        ->br()
                        ->append('     * Store new resource.')
                        ->br()
                        ->append('     *')
                        ->br()
                        ->append('     * @param \Voyager\App\Request $request')
                        ->br()
                        ->append('     * @return mixed')
                        ->br()
                        ->append('     */')
                        ->br()
                        ->br()
                        ->append('    protected function store(Request $request)')
                        ->br()
                        ->append('    {')
                        ->br()
                        ->append('        return null;')
                        ->br()
                        ->append('    }')
                        ->br()
                        ->br()
                        ->append('    /**')
                        ->br()
                        ->append('     * Make changes to resource.')
                        ->br()
                        ->append('     *')
                        ->br()
                        ->append('     * @param \Voyager\App\Request $request')
                        ->br()
                        ->append('     * @return mixed')
                        ->br()
                        ->append('     */')
                        ->br()
                        ->br()
                        ->append('    protected function update(Request $request)')
                        ->br()
                        ->append('    {')
                        ->br()
                        ->append('        return null;')
                        ->br()
                        ->append('    }')
                        ->br()
                        ->br()
                        ->append('    /**')
                        ->br()
                        ->append('     * Delete a single or multiple resources.')
                        ->br()
                        ->append('     *')
                        ->br()
                        ->append('     * @param \Voyager\App\Request $request')
                        ->br()
                        ->append('     * @return mixed')
                        ->br()
                        ->append('     */')
                        ->br()
                        ->br()
                        ->append('    protected function destroy(Request $request)')
                        ->br()
                        ->append('    {')
                        ->br()
                        ->append('        return null;')
                        ->br()
                        ->append('    }');
            }

            $builder->br()
                    ->br()
                    ->append('}');

            $dir->make($filename, $builder->get());
            $msg->success('Controller class file was successfully created.');
        }
        else
        {
            $msg->error('Controller class file already exist.');
        }
    }

    /**
     * Make middleware class file.
     * 
     * @param   string $filename
     * @return  void
     */

    protected function middleware(string $filename)
    {
        $dir = $this->manageFolder('app/Middleware', 'Middleware');
        $classname = ucfirst($filename);
        $filename = $classname . '.php';
        $msg = new Message();

        if(!$dir->has($filename))
        {
            $builder = new Builder('<?php');
            $builder->br()
                    ->br()
                    ->append('namespace App\Middleware;')
                    ->br()
                    ->br()
                    ->append('use Voyager\App\Middleware;')
                    ->br()
                    ->append('use Voyager\App\Request;')
                    ->br()
                    ->br()
                    ->append('class ' . $classname)
                    ->append(' extends Middleware')
                    ->br()
                    ->append('{')
                    ->br()
                    ->br()
                    ->append('    protected function handle(Request $request)')
                    ->br()
                    ->append('    {')
                    ->br()
                    ->br()
                    ->append('    }')
                    ->br()
                    ->br()
                    ->append('}');

            $dir->make($filename, $builder->get());
            $msg->success('Middleware class file was successfully created.');
        }
        else
        {
            $msg->error('Middleware class file already exist.');
        }
    }

    /**
     * Make new console class file.
     * 
     * @param   string $filename
     * @return  void
     */

    protected function console(string $filename)
    {
        $dir = $this->manageFolder('app/Console', 'Console');
        $classname = ucfirst($filename);
        $filename = $classname . '.php';
        $msg = new Message();

        if(!$dir->has($filename))
        {
            $builder = new Builder('<?php');
            $builder->br()
                    ->br()
                    ->append('namespace App\Console;')
                    ->br()
                    ->br()
                    ->append('use Voyager\App\Console;')
                    ->br()
                    ->br()
                    ->append('class ' . $classname)
                    ->append(' extends Console')
                    ->br()
                    ->append('{')
                    ->br()
                    ->br()
                    ->append('    protected function main()')
                    ->br()
                    ->append('    {')
                    ->br()
                    ->br()
                    ->append('    }')
                    ->br()
                    ->br()
                    ->append('}');

            $dir->make($filename, $builder->get());
            $msg->success('Console class file was successfully created.');
        }
        else
        {
            $msg->error('Console class file already exist.');
        }
    }

    /**
     * Make new transformer class file.
     * 
     * @param   string $filename
     * @return  void
     */

    protected function transformer(string $filename)
    {
        $dir = $this->manageFolder('app/Transformer', 'Transformer');
        $classname = ucfirst($filename);
        $filename = $classname . '.php';
        $msg = new Message();

        if(!$dir->has($filename))
        {
            $builder = new Builder('<?php');
            $builder->br()
                    ->br()
                    ->append('namespace App\Transformer;')
                    ->br()
                    ->br()
                    ->append('use Voyager\Resource\Transformer;')
                    ->br()
                    ->append('use Voyager\Util\Data\Collection;')
                    ->br()
                    ->br()
                    ->append('class ' . $classname)
                    ->append(' extends Transformer')
                    ->br()
                    ->append('{')
                    ->br()
                    ->br()
                    ->append('    protected function transform(Collection $data)')
                    ->br()
                    ->append('    {')
                    ->br()
                    ->append('      return [];')
                    ->br()
                    ->append('    }')
                    ->br()
                    ->br()
                    ->append('}');

            $dir->make($filename, $builder->get());
            $msg->success('Transformer class file was successfully created.');
        }
        else
        {
            $msg->error('Transformer class file already exist.');
        }
    }

    /**
     * Make new model and service class files.
     * 
     * @param   string $name
     * @return  void
     */

    protected function model(string $name)
    {
        $dir = $this->manageFolder('app/Database', 'Database');
        $classname = ucfirst($name);
        $filename = $classname . '.php';
        $msg = new Message();

        if(!File::exist('app/Database/Model'))
        {
            $dir->makeFolder('Model');
            $msg->success('Model folder was successfully created.');
        }

        if(!File::exist('app/Database/Service'))
        {
            $dir->makeFolder('Service');
            $msg->success('Service folder was successfully created.');
        }

        $model = new Directory('app/Database/Model');

        if(!$model->has($filename))
        {
            $builder = new Builder('<?php');
            $builder->br()
                    ->br()
                    ->append('namespace App\Database\Model;')
                    ->br()
                    ->br()
                    ->append('use Voyager\Database\Model;')
                    ->br()
                    ->append('use Voyager\Database\Schema;')
                    ->br()
                    ->br()
                    ->append('class ' . $classname)
                    ->append(' extends Model')
                    ->br()
                    ->append('{')
                    ->br()
                    ->br()
                    ->append('    protected static function migrate(Schema $schema)')
                    ->br()
                    ->append('    {')
                    ->br()
                    ->br()
                    ->append('    }')
                    ->br()
                    ->br()
                    ->append('}');

            $model->make($filename, $builder->get());
            $msg->success('Model class file was successfully created.');
        }
        else
        {
            $msg->error('Model class file already existed.');
        }

        $service = new Directory('app/Database/Service');

        if(!$service->has($filename))
        {
            $builder = new Builder('<?php');
            $builder->br()
                    ->br()
                    ->append('namespace App\Database\Service;')
                    ->br()
                    ->br()
                    ->append('use Voyager\Database\Service;')
                    ->br()
                    ->br()
                    ->append('class ' . $classname)
                    ->append(' extends Service')
                    ->br()
                    ->append('{')
                    ->br()
                    ->br()
                    ->append('}');
            
            $service->make($filename, $builder->get());
            $msg->success('Service class file was successfully created.');
        }
        else
        {
            $msg->error('Service class file already existed.');
        }
    }

    /**
     * Make new locale file.
     * 
     * @param   string $filename
     * @return  void
     */

    protected function locale(string $filename)
    {
        $dir = $this->manageFolder('resource/locale', 'locale');
        $msg = new Message();
        $filename .= '.php';

        if(!$dir->has($filename))
        {
            $builder = new Builder('<?php');
            $builder->br()
                    ->br()
                    ->append('namespace Voyager\Resource\Locale {')
                    ->br()
                    ->br()
                    ->br()
                    ->br()
                    ->append('}');

            $dir->make($filename, $builder->get());
            $msg->success('Locale file was successfully created.');
        }
        else
        {
            $msg->error('Locale file already exist.');
        }
    }

    /**
     * Create new route file.
     * 
     * @param   string $filename
     * @return  void
     */

    protected function route(string $filename, array $arg)
    {
        $msg = new Message();
        $dir = new Directory('routes/');
        $prefix = strtolower($filename);
        $filename .= '.php';
        $group = $this->hasSecondParameter('-g', $arg);
        
        if(!$dir->exist())
        {
            mkdir('routes');
            $msg->success('Routes folder was successfully created.');
        }

        if(!$dir->has($filename))
        {
            $builder = new Builder('<?php');
            $builder->br()
                    ->br()
                    ->append('namespace Voyager\Http\Route {')
                    ->br()
                    ->br();

            if($group)
            {
            $builder->append('    Route::group(\'' . $prefix . '\', function(Setting $setting) {')
                    ->br()
                    ->br()
                    ->br()
                    ->br()
                    ->append('    });');
            }

            $builder->br()
                    ->br()
                    ->append('}');
            
            $dir->make($filename, $builder->get());
            $msg->success('Route file was successfully created.');
        }
        else
        {
            $msg->error('Route file already exist.');
        }
    }

    /**
     * Create new validation class file.
     * 
     * @param   string $filename
     * @return  void
     */

    protected function validation(string $filename)
    {
        $msg = new Message();
        $dir = $this->manageFolder('app/Validation', 'Validation');
        $classname = ucfirst($filename);
        $filename .= '.php';

        if(!$dir->has($filename))
        {
            $builder = new Builder('<?php');
            $builder->br()
                    ->br()
                    ->append('namespace App\Validation;')
                    ->br()
                    ->br()
                    ->append('use Voyager\App\Validation;')
                    ->br()
                    ->append('use Voyager\App\Parameter;')
                    ->br()
                    ->br()
                    ->append('class ' . $classname)
                    ->append(' extends Validation')
                    ->br()
                    ->append('{')
                    ->br()
                    ->br()
                    ->append('    protected function param(Parameter $parameter)')
                    ->br()
                    ->append('    {')
                    ->br()
                    ->br()
                    ->append('    }')
                    ->br()
                    ->br()
                    ->append('}');

            $dir->make($filename, $builder->get());
            $msg->success('Validation class file was successfully created.');
        }
        else
        {
            $msg->error('Validation class file already exist.');
        }
    }

    /**
     * Create new resource files.
     * 
     * @param   string $name
     * @return  void
     */

    protected function resource(string $name)
    {
        $msg = new Message();
        $output = [];

        exec('php atmos make:controller ' . ucfirst($name) . 'Controller -r', $output);
        exec('php atmos make:model ' . ucfirst($name), $output);

        foreach($output as $message)
        {
            $msg->set($message);
        }

        $msg->success('Resource was successfully created.');
    }

    /**
     * Create new view content file.
     * 
     * @param   string $name
     * @return  void
     */

    protected function content(string $name)
    {
        $msg = new Message();
        $dir = $this->manageFolder('resource/view/content', 'content', false);
        $filename = $name . '.html';        

        if(!$dir->has($filename))
        {
            $builder = new Builder('<template extend="master">');
            $builder->br()
                    ->br()
                    ->br()
                    ->append('</template>')
                    ->br()
                    ->br()
                    ->append('<script type="text/javascript">')
                    ->br()
                    ->br()
                    ->br()
                    ->append('</script>')
                    ->br()
                    ->br()
                    ->append('<style type="text/css">')
                    ->br()
                    ->br()
                    ->br()
                    ->append('</style>');

            $dir->make($filename, $builder->get());
            $msg->success('View content file was successfully created.');
        }
        else
        {
            $msg->error('View content file already exist.');
        }
    }

    /**
     * Create new view component file.
     * 
     * @param   string $name
     * @return  void
     */

    protected function component(string $name)
    {
        $msg = new Message();
        $this->manageFolder('resource/view/component', 'component', false);
        $name = ucfirst($name);

        if(!Dir::exist('resource/view/component/' . $name))
        {
            $folder = $this->manageFolder('resource/view/component/' . $name, $name);

            $class = new Builder('<?php');
            $class->br()
                  ->br()
                  ->append('namespace Components\\' . $name . ';')
                  ->br()
                  ->br()
                  ->append('use Voyager\App\Components;')
                  ->br()
                  ->br()
                  ->append('class ' . $name . ' extends Components')
                  ->br()
                  ->append('{')
                  ->br()
                  ->br()
                  ->br()
                  ->br()
                  ->append('}');  

            $folder->make($name . '.php', $class->get());
            $msg->success('Component class file was successfully created.');

            $builder = new Builder('<template>');
            $builder->br()
                    ->br()
                    ->br()
                    ->append('</template>')
                    ->br()
                    ->br()
                    ->append('<script type="text/javascript">')
                    ->br()
                    ->br()
                    ->br()
                    ->append('</script>')
                    ->br()
                    ->br()
                    ->append('<style type="text/css">')
                    ->br()
                    ->br()
                    ->br()
                    ->append('</style>');

            $folder->make($name . '.html', $builder->get());
            $msg->success('Component template file was successfully created.');
        }
        else
        {
            $msg->error('Component folder already exist from the directory.');
        }
    }

    /**
     * Create new view section file.
     * 
     * @param   string $name
     * @return  void
     */

    protected function section(string $name)
    {
        $msg = new Message();
        $dir = $this->manageFolder('resource/view/section', 'section', false);
        $filename = $name . '.html';

        if(!$dir->has($filename))
        {
            $builder = new Builder('<template>');
            $builder->br()
                    ->br()
                    ->br()
                    ->append('</template>')
                    ->br()
                    ->br()
                    ->append('<script type="text/javascript">')
                    ->br()
                    ->br()
                    ->br()
                    ->append('</script>')
                    ->br()
                    ->br()
                    ->append('<style type="text/css">')
                    ->br()
                    ->br()
                    ->br()
                    ->append('</style>');

            $dir->make($filename, $builder->get());
            $msg->success('View section file was successfully created.');
        }
        else
        {
            $msg->error('View section file already exist.');
        }
    }

}