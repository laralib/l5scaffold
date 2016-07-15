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
use Laralib\L5scaffold\Localizations\SchemaParser as LocalizationsParser;
use Laralib\L5scaffold\Localizations\SyntaxBuilder as LocalizationsBuilder;

class MakeLocalization
{
    use MakerTrait;

    private $language_code;

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
        $this->language_code = $this->scaffoldCommandObj->option('lang');

        $this->start();
    }

    /**
     * Start make seed.
     *
     * @return void
     */
    protected function start()
    {
        $path = $this->getPath($this->language_code . '/'.$this->scaffoldCommandObj->getObjName('Name'), 'localization');

        $this->makeDirectory($path);

        if ($this->files->exists($path))
        {
            if ($this->scaffoldCommandObj->confirm($path . ' already exists! Do you wish to overwrite? [yes|no]'))
            {
                $this->files->put($path, $this->compileLocalizationStub());
                $this->getSuccessMsg();
            }
        }
        else
        {
            $this->files->put($path, $this->compileLocalizationStub());
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
        $this->scaffoldCommandObj->info('Localization created successfully.');
    }

    /**
     * Compile the migration stub.
     *
     * @return string
     */
    protected function compileLocalizationStub()
    {
        $stub = $this->files->get(__DIR__ . '/../Stubs/localization.stub');

        $this->build($stub);

        return $stub;
    }

    private function build(&$stub){
        $this->replaceLocalization($stub);
    }

    private function replaceLocalization(&$stub){
        if($schema = $this->scaffoldCommandObj->option('localization')){
            $schema = (new LocalizationsParser())->parse($schema);
        }

        $schema = (new LocalizationsBuilder())->create($schema, $this->scaffoldCommandObj->getMeta(), 'validation');
        $stub = str_replace('{{localization}}', $schema, $stub);

        return $this;
    }

}