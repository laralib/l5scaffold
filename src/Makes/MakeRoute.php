<?php
/**
 * Created by PhpStorm.
 * User: fernandobritofl
 * Date: 4/22/15
 * Time: 10:34 PM
 */

namespace Laralib\L5scaffold\Makes;

use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Filesystem\Filesystem;
use Laralib\L5scaffold\Commands\ScaffoldMakeCommand;
use Laralib\L5scaffold\Migrations\SchemaParser;
use Laralib\L5scaffold\Migrations\SyntaxBuilder;

class MakeRoute
{
    use AppNamespaceDetectorTrait, MakerTrait;

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
     * Start make controller.
     *
     * @return void
     */
    private function start()
    {
        $name = $this->scaffoldCommandObj->getObjName('Name');
        $path = $this->getPath($name, 'route');

        $this->files->append($path, $this->compileRouteStub());

        $this->scaffoldCommandObj->info('Routes Updated successfully.');
    }

    /**
     * Compile the migration stub.
     *
     * @return string
     */
    protected function compileRouteStub()
    {
        $stub = $this->files->get(substr(__DIR__,0, -5) . 'Stubs/route.stub');

        $this->build($stub);

        return $stub;
    }

    /**
     * Build stub replacing the variable template.
     *
     * @return string
     */
    protected function build(&$stub)
    {
        $namespace = $model_name = $this->getAppNamespace();
        $Name = $this->scaffoldCommandObj->getObjName('Name');
        $name = $this->scaffoldCommandObj->getObjName('name');
        $names =  $this->scaffoldCommandObj->getObjName('names');
        $prefix = $this->scaffoldCommandObj->option('prefix');

        if(!empty($prefix)) $prefix = "$prefix.";

        $stub = str_replace('{{model_namespace}}', $namespace.$Name, $stub);
        $stub = str_replace('{{model_class}}', $Name, $stub);
        $stub = str_replace('{{model_variable}}', $name, $stub);
        $stub = str_replace('{{model_multiple}}', $names, $stub);
        $stub = str_replace('{{prefix}}', $prefix, $stub);

        return $this;
    }
}