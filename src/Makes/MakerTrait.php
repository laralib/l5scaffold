<?php
/**
 * Created by PhpStorm.
 * User: fernandobritofl
 * Date: 4/21/15
 * Time: 5:00 PM
 */

namespace Laralib\L5scaffold\Makes;

use Illuminate\Filesystem\Filesystem;
use Laralib\L5scaffold\Commands\ScaffoldMakeCommand;

trait MakerTrait
{

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;
    protected $scaffoldCommandM;

    /**
     * @param ScaffoldMakeCommand $scaffoldCommand
     * @param Filesystem $files
     */
    public function __construct(ScaffoldMakeCommand $scaffoldCommand, Filesystem $files)
    {
        $this->files = $files;
        $this->scaffoldCommandM = $scaffoldCommand;

        $this->generateNames($this->scaffoldCommandM);
    }

    /**
     * Get stub path.
     *
     * @param $file_name
     * @param string $path
     * @return string
     */
    protected function getStubPath()
    {
        return substr(__DIR__,0, -5) . 'Stubs';
    }

    /**
     * Build file replacing metas in template.
     *
     * @param array $metas
     * @param string &$template
     * @return void
     */
    protected function buildStub(array $metas, &$template)
    {
        foreach($metas as $k => $v)
        {
            $template = str_replace("{{". $k ."}}", $v, $template);
        }
    }

    /**
     * Get the path to where we should store the controller.
     *
     * @param $file_name
     * @param string $path
     * @return string
     */
    protected function getPath($file_name, $path='controller')
    {
        if($path == "controller")
        {
            return './app/Http/Controllers/' . $file_name . '.php';
        }
        elseif($path == "model")
        {
            return './app/'.$file_name.'.php';
        }
        elseif($path == "seed")
        {
            return './database/seeds/'.$file_name.'.php';
        }
        elseif($path == "view-index")
        {
            return './resources/views/'.$file_name.'/index.blade.php';
        }
        elseif($path == "view-edit")
        {
            return './resources/views/'.$file_name.'/edit.blade.php';
        }
        elseif($path == "view-show")
        {
            return './resources/views/'.$file_name.'/show.blade.php';
        }
        elseif($path == "view-create")
        {
            return './resources/views/'.$file_name.'/create.blade.php';
        }
        elseif($path == "localization"){
            return './resources/lang/'.$file_name.'.php';
        }
        elseif($path == "route"){
            return './app/Http/routes.php';
        }
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if ( ! $this->files->isDirectory(dirname($path)))
        {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }
}