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
use Laralib\L5scaffold\Migrations\SchemaParser;
use Laralib\L5scaffold\Migrations\SyntaxBuilder;

class MakeModel
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
     * Start make controller.
     *
     * @return void
     */
    private function start()
    {
        $name = $this->scaffoldCommandObj->getObjName('Name');
        $path = $this->getPath($name, 'model');

        if ($this->files->exists($path)) 
        {
            return $this->scaffoldCommandObj->error($name . ' already exists!');
        }

        $this->files->put($path, $this->compileModelStub());

        $this->scaffoldCommandObj->info('Model created successfully.');
    }

    /**
     * Compile the migration stub.
     *
     * @return string
     */
    protected function compileModelStub()
    {
        $stub = $this->files->get(substr(__DIR__,0, -5) . 'Stubs/model.stub');

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
        $Name = $this->scaffoldCommandObj->getObjName('Name');
        $schema = $this->scaffoldCommandObj->option('schema');
        $schemaArray = [];

        if ($schema)
        {
            $schemaArray = array_map(function($item){
                return "'".$item['name']."'";
            }, (new SchemaParser)->parse($schema));

            $schemaArray = join(", ", $schemaArray);
        }


        $stub = str_replace('{{model_class}}', $Name, $stub);
        $stub = str_replace('{{model_fillable}}', $schemaArray, $stub);

        return $this;
    }
}