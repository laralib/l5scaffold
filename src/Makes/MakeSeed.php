<?php
/**
 * Created by PhpStorm.
 * User: fernandobritofl
 * Date: 4/22/15
 * Time: 10:34 PM
 */

namespace Laralib\L5scaffold\Makes;


use Illuminate\Filesystem\Filesystem;
use Laralib\L5scaffold\Commands\ScaffoldMakeCommand;

class MakeSeed
{
    use MakerTrait;

    public function __construct(ScaffoldMakeCommand $scaffoldCommand, Filesystem $files)
    {
        $this->files = $files;
        $this->scaffoldCommandObj = $scaffoldCommand;

        $this->start();
    }


    protected function start()
    {


        // Get path
        $path = $this->getPath($this->scaffoldCommandObj->getObjName('Name') . 'TableSeeder', 'seed');


        // Create directory
        $this->makeDirectory($path);


        if ($this->files->exists($path)) {
            if ($this->scaffoldCommandObj->confirm($path . ' already exists! Do you wish to overwrite? [yes|no]')) {
                // Put file
                $this->files->put($path, $this->compileSeedStub());
                $this->getSuccessMsg();
            }
        } else {

            // Put file
            $this->files->put($path, $this->compileSeedStub());
            $this->getSuccessMsg();

        }

    }


    protected function getSuccessMsg()
    {
        $this->scaffoldCommandObj->info('Seed created successfully.');
    }


    /**
     * Compile the migration stub.
     *
     * @return string
     */
    protected function compileSeedStub()
    {
        $stub = $this->files->get(__DIR__ . '/../stubs/seed.stub');

        $this->replaceClassName($stub);


        return $stub;
    }


    private function replaceClassName(&$stub)
    {
        $name = $this->scaffoldCommandObj->getObjName('Name');

        $stub = str_replace('{{class}}', $name, $stub);

        return $this;
    }


}