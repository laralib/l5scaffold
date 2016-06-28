<?php
/**
 * Created by PhpStorm.
 * User: fernandobritofl
 * Date: 4/21/15
 * Time: 4:58 PM
 */

namespace Laralib\L5scaffold\Makes;


use Illuminate\Filesystem\Filesystem;
use Laralib\L5scaffold\Commands\ScaffoldMakeCommand;
use Laralib\L5scaffold\Migrations\SchemaParser;
use Laralib\L5scaffold\Migrations\SyntaxBuilder;

class MakeView
{
    use MakerTrait;


    protected $scaffoldCommandObj;
    protected $viewName;
    protected $schemaArray = [];

    public function __construct(ScaffoldMakeCommand $scaffoldCommand, Filesystem $files, $viewName)
    {
        $this->files = $files;
        $this->scaffoldCommandObj = $scaffoldCommand;
        $this->viewName = $viewName;
        $this->getSchemaArray();

        $this->start();
    }

    private function start()
    {
        $this->generateView($this->viewName); // index, show, edit and create
    }


    protected function getSchemaArray()
    {
      if($this->scaffoldCommandObj->option('schema') != null){
        if ($schema = $this->scaffoldCommandObj->option('schema')) {
          $this->schemaArray = (new SchemaParser)->parse($schema);
        }
      }
    }


    protected function generateView($nameView = 'index'){
        // Get path
        $path = $this->getPath($this->scaffoldCommandObj->getObjName('names'), 'view-'.$nameView);


        // Create directory
        $this->makeDirectory($path);


        if ($this->files->exists($path)) {
            if ($this->scaffoldCommandObj->confirm($path . ' already exists! Do you wish to overwrite? [yes|no]')) {
                // Put file
                $this->files->put($path, $this->compileViewStub($nameView));
            }
        } else {

            // Put file
            $this->files->put($path, $this->compileViewStub($nameView));
        }


    }






    /**
     * Compile the migration stub.
     *
     * @param $nameView
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function compileViewStub($nameView)
    {
        $stub = $this->files->get(__DIR__ . '/../stubs/html_assets/'.$nameView.'.stub');

        if($nameView == 'show'){
            // show.blade.php
            $this->replaceName($stub)
                ->replaceSchemaShow($stub);

        } elseif($nameView == 'edit'){
            // edit.blade.php
            $this->replaceName($stub)
                ->replaceSchemaEdit($stub);

        } elseif($nameView == 'create'){
            // edit.blade.php
            $this->replaceName($stub)
                ->replaceSchemaCreate($stub);

        } else {
            // index.blade.php
            $this->replaceName($stub)
                ->replaceSchemaIndex($stub);
        }



        return $stub;
    }


    /**
     * Replace the class name in the stub.
     *
     * @param  string $stub
     * @return $this
     */
    protected function replaceName(&$stub)
    {
        $stub = str_replace('{{Class}}', $this->scaffoldCommandObj->getObjName('Names'), $stub);
        $stub = str_replace('{{class}}', $this->scaffoldCommandObj->getObjName('names'), $stub);
        $stub = str_replace('{{classSingle}}', $this->scaffoldCommandObj->getObjName('name'), $stub);

        $prefix = $this->scaffoldCommandObj->option('prefix');

        if ($prefix != null)
            $stub = str_replace('{{prefix}}',$prefix.'.', $stub);
        else
            $stub = str_replace('{{prefix}}', '', $stub);

        return $this;
    }





    /**
     * Replace the schema for the index.stub.
     *
     * @param  string $stub
     * @return $this
     */
    protected function replaceSchemaIndex(&$stub)
    {

        // Create view index header fields
        $schema = (new SyntaxBuilder)->create($this->schemaArray, $this->scaffoldCommandObj->getMeta(), 'view-index-header');
        $stub = str_replace('{{header_fields}}', $schema, $stub);


        // Create view index content fields
        $schema = (new SyntaxBuilder)->create($this->schemaArray, $this->scaffoldCommandObj->getMeta(), 'view-index-content');
        $stub = str_replace('{{content_fields}}', $schema, $stub);


        return $this;
    }





    /**
     * Replace the schema for the show.stub.
     *
     * @param  string $stub
     * @return $this
     */
    protected function replaceSchemaShow(&$stub)
    {

        // Create view index content fields
        $schema = (new SyntaxBuilder)->create($this->schemaArray, $this->scaffoldCommandObj->getMeta(), 'view-show-content');
        $stub = str_replace('{{content_fields}}', $schema, $stub);

        return $this;
    }


    /**
     * Replace the schema for the edit.stub.
     *
     * @param  string $stub
     * @return $this
     */
    private function replaceSchemaEdit(&$stub)
    {

        // Create view index content fields
        $schema = (new SyntaxBuilder)->create($this->schemaArray, $this->scaffoldCommandObj->getMeta(), 'view-edit-content', $this->scaffoldCommandObj->option('form'));
        $stub = str_replace('{{content_fields}}', $schema, $stub);

        return $this;

    }


    /**
     * Replace the schema for the edit.stub.
     *
     * @param  string $stub
     * @return $this
     */
    private function replaceSchemaCreate(&$stub)
    {


        // Create view index content fields
        $schema = (new SyntaxBuilder)->create($this->schemaArray, $this->scaffoldCommandObj->getMeta(), 'view-create-content', $this->scaffoldCommandObj->option('form'));
        $stub = str_replace('{{content_fields}}', $schema, $stub);

        return $this;

    }

}
