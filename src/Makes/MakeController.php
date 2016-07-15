<?php
namespace Laralib\L5scaffold\Makes;

use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Filesystem\Filesystem;
use Laralib\L5scaffold\Commands\ScaffoldMakeCommand;
use Laralib\L5scaffold\Validators\SchemaParser as ValidatorParser;
use Laralib\L5scaffold\Validators\SyntaxBuilder as ValidatorSyntax;


class MakeController
{
    use AppNamespaceDetectorTrait, MakerTrait;

    /**
     * Store name from Model
     *
     * @var ScaffoldMakeCommand
     */
    protected $scaffoldCommandObj;

    /**
     * Create a new instance.
     *
     * @param ScaffoldMakeCommand $scaffoldCommand
     * @param Filesystem $files
     * @return void
     */
    function __construct(ScaffoldMakeCommand $scaffoldCommand, Filesystem $files)
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
        $name = $this->scaffoldCommandObj->getObjName('Name') . 'Controller';
        $path = $this->getPath($name, 'controller');

        if ($this->files->exists($path)) 
        {
            return $this->scaffoldCommandObj->error($name . ' already exists!');
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->compileControllerStub());

        $this->scaffoldCommandObj->info('Controller created successfully.');
    }

    /**
     * Compile the migration stub.
     *
     * @return string
     */
    protected function compileControllerStub()
    {
        $stub = $this->files->get(__DIR__ . '/../Stubs/controller.stub');

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

        $this->replaceValidator($stub);

        $stub = str_replace('{{model_namespace}}', $namespace.$Name, $stub);
        $stub = str_replace('{{model_class}}', $Name, $stub);
        $stub = str_replace('{{model_variable}}', $name, $stub);
        $stub = str_replace('{{model_multiple}}', $names, $stub);
        $stub = str_replace('{{prefix}}', $prefix, $stub);

        return $this;
    }

    private function replaceValidator(&$stub)
    {
        if($schema = $this->scaffoldCommandObj->option('validator')){
            $schema = (new ValidatorParser)->parse($schema);
        }

        $schema = (new ValidatorSyntax)->create($schema, $this->scaffoldCommandObj->getMeta(), 'validation');
        $stub = str_replace('{{validation_fields}}', $schema, $stub);

        return $this;
    }


}