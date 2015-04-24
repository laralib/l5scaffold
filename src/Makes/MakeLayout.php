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

class MakeLayout {
    use MakerTrait;

    public function __construct(ScaffoldMakeCommand $scaffoldCommand, Filesystem $files)
    {
        $this->files = $files;
        $this->scaffoldCommandObj = $scaffoldCommand;

        $this->start();
    }


    protected function start()
    {

        if ($this->files->exists($path_resource = $this->getPathResource('layout'))) {
            if ($this->scaffoldCommandObj->confirm($path_resource . ' already exists! Do you wish to overwrite? [yes|no]')) {
                $this->putViewLayout($path_resource);

                $this->scaffoldCommandObj->info('Layout created successfully.');
            } else {
                $this->scaffoldCommandObj->comment('Skip Layout, because already exists.');
            }
        } else {
            $this->putViewLayout($path_resource);
        }


    }


    /**
     * @param $path_resource
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function putViewLayout($path_resource)
    {
        // Copy layout blade bootstrap 3 to resoures
        $layout_html = $this->files->get(__DIR__ . '/../stubs/html_assets/layout.stub');
        $this->files->put($path_resource, $layout_html);
    }



    /**
     * Get the path to where we should store the view.
     *
     * @return string
     */
    protected function getPathResource()
    {

            return './resources/views/layout.blade.php';

    }
}