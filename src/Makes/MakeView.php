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

    /**
     * Store scaffold command.
     *
     * @var ScaffoldMakeCommand
     */
    protected $scaffoldCommandObj;

    /**
     * Store property of model
     *
     * @var array
     */
    protected $schemaArray = [];

    /**
     * Create a new instance.
     *
     * @param ScaffoldMakeCommand $scaffoldCommand
     * @param Filesystem $files
     * @param sting $viewName
     * @return void
     */
    public function __construct(ScaffoldMakeCommand $scaffoldCommand, Filesystem $files)
    {
        $this->files = $files;
        $this->scaffoldCommandObj = $scaffoldCommand;

        $this->start();
    }

    /**
     * Get all property of model
     *
     * @return void
     */
    protected function getSchemaArray()
    {
        // ToDo - schema is required?
        if($this->scaffoldCommandObj->option('schema') != null)
        {
            if ($schema = $this->scaffoldCommandObj->option('schema'))
            {
                return (new SchemaParser)->parse($schema);
            }
        }

        return [];
    }

    /**
     * Start make view.
     *
     * @return void
     */
    private function start()
    {
        $this->scaffoldCommandObj->line("\n--- Views ---");

        $viewsFiles = $this->getStubViews();
        $destination = $this->getDestinationViews($this->scaffoldCommandObj->getMeta()['models']);
        $metas = array_merge_recursive
        (
            $this->scaffoldCommandObj->getMeta(),
            [
                'form_fields_fillable' => $this->getFieldsFillable(),
                'form_fields_empty' => $this->getFieldsEmpty(),
                'content_fields' => $this->getFieldsIndex(),
                'header_fields' => $this->getFieldsHeaderIndex()
            ]
        );

        foreach ($viewsFiles as $viewFileBaseName => $viewFile)
        {
            $viewFileName = str_replace('.stub', '', $viewFileBaseName);
            $viewDestination = $destination . $viewFileName;

            if ($this->files->exists($viewDestination))
            {
                $this->scaffoldCommandObj->comment("   x $viewFileName");
                continue;
            }

            $stub = $this->files->get($viewFile);
            $stub = $this->buildStub($metas, $stub);
            
            $this->makeDirectory($viewDestination);
            $this->files->put($viewDestination, $stub);
            $this->scaffoldCommandObj->info("   + $viewFileName");
        }
    }

    private function getFieldsFillable()
    {
        return (new SyntaxBuilder)->create(
            $this->getSchemaArray(), 
            $this->scaffoldCommandObj->getMeta(), 
            'view-edit-content',
            $this->scaffoldCommandObj->option('form')
        );
    }

    private function getFieldsEmpty()
    {
        return (new SyntaxBuilder)->create(
            $this->getSchemaArray(), 
            $this->scaffoldCommandObj->getMeta(), 
            'view-create-content',
            $this->scaffoldCommandObj->option('form')
        );
    }

    private function getFieldsIndex()
    {
        return (new SyntaxBuilder)->create(
            $this->getSchemaArray(),
            $this->scaffoldCommandObj->getMeta(),
            'view-index-content'
        );
    }

    private function getFieldsHeaderIndex()
    {
        return (new SyntaxBuilder)->create(
            $this->getSchemaArray(),
            $this->scaffoldCommandObj->getMeta(), 
            'view-index-header'
        );
    }
}