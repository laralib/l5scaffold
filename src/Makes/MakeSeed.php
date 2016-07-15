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
     * Start make seed.
     *
     * @return void
     */
    protected function start()
    {
        $path = $this->getPath($this->scaffoldCommandObj->getObjName('Name') . 'TableSeeder', 'seed');

        $this->makeDirectory($path);

        if ($this->files->exists($path))
        {
            if ($this->scaffoldCommandObj->confirm($path . ' already exists! Do you wish to overwrite? [yes|no]'))
            {
                $this->files->put($path, $this->compileSeedStub());
                $this->getSuccessMsg();
            }
        }
        else
        {
            $this->files->put($path, $this->compileSeedStub());
            $this->getSuccessMsg();
        }
    }

    /**
     * Command to show info in console
     *
     * @return void
     */
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
        $stub = $this->files->get(substr(__DIR__,0, -5) . 'Stubs/seed.stub');

        $this->replaceClassName($stub);

        return $stub;
    }

    /**
     * Rename the class name in Seed
     *
     * @return $this
     */
    private function replaceClassName(&$stub)
    {
        $name = $this->scaffoldCommandObj->getObjName('Name');

        $stub = str_replace('{{class}}', $name, $stub);

        return $this;
    }
}