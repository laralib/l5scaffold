<?php
/**
 * Created by PhpStorm.
 * User: fernandobritofl
 * Date: 4/22/15
 * Time: 11:49 PM
 */

namespace Laralib\L5scaffold\Makes;

use Illuminate\Filesystem\Filesystem;
use Laralib\L5scaffold\Commands\ScaffoldMakeCommand;

class MakeLayout
{
    use MakerTrait;

    /**
     * Create a new instance.
     *
     * @param ScaffoldMakeCommand $scaffoldCommand
     * @param Filesystem $files
     * @return void
     */
    public function __construct(ScaffoldMakeCommand $scaffoldCommand, Filesystem $files)
    {
        $this->files = $files;
        $this->scaffoldCommandObj = $scaffoldCommand;

        $this->start();
    }

    /**
     * Start make layout(view).
     *
     * @return void
     */
    protected function start()
    {
        $this->putViewLayout('Layout', 'Stubs/html_assets/layout.stub', 'layout.blade.php');
        $this->putViewLayout('Error', 'Stubs/html_assets/error.stub', 'error.blade.php');
    }


    /**
     * Write layout in path
     *
     * @param $path_resource
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function putViewLayout($name, $stub, $file)
    {
        $path_file = $this->getPathResource().$file;
        $path_stub = __DIR__ .'/../'.$stub;

        if (!$this->files->exists($path_file))
        {
            $html = $this->files->get($path_stub);
            $this->files->put($path_file, $html);

            $this->scaffoldCommandObj->info("$name created successfully.");
        }
        else
        {
            $this->scaffoldCommandObj->comment("Skip $name, because already exists.");
        }
    }

    /**
     * Get the path to where we should store the view.
     *
     * @return string
     */
    protected function getPathResource()
    {
        return './resources/views/';
    }
}