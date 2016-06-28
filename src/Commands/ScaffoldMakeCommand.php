<?php

namespace Laralib\L5scaffold\Commands;

use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Laralib\L5scaffold\Makes\MakeController;
use Laralib\L5scaffold\Makes\MakeLayout;
use Laralib\L5scaffold\Makes\MakeMigration;
use Laralib\L5scaffold\Makes\MakeModel;
use Laralib\L5scaffold\Makes\MakerTrait;
use Laralib\L5scaffold\Makes\MakeSeed;
use Laralib\L5scaffold\Makes\MakeView;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ScaffoldMakeCommand extends Command
{
    use AppNamespaceDetectorTrait, MakerTrait;

    /**
     * The console command name!
     *
     * @var string
     */
    protected $name = 'make:scaffold';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a scaffold with bootstrap 3';


    /**
     * Meta information for the requested migration.
     *
     * @var array
     */
    protected $meta;

    /**
     * @var Composer
     */
    private $composer;


    /**
     * Views to generate
     *
     * @var array
     */
    private $views = ['index', 'create', 'show', 'edit'];

    /**
     * Store name from Model
     * @var string
     */
    private $nameModel = "";

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     * @param Composer $composer
     */
    public function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct();


        $this->files = $files;
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        // Start Scaffold
        $this->info('Configuring ' . $this->getObjName("Name") . '...');

        // Setup migration and saves configs
        $this->meta['action'] = 'create';
        $this->meta['var_name'] = $this->getObjName("name");
        $this->meta['table'] = $this->getObjName("names"); // Store table name

        // Generate files
        $this->makeMigration();
        $this->makeSeed();
        $this->makeModel();
        $this->makeController();
        $this->makeViewLayout();
        $this->makeViews();


    }


    /**
     * Generate the desired migration.
     */
    protected function makeMigration()
    {
        new MakeMigration($this, $this->files);
    }


    /**
     * Generate an Eloquent model, if the user wishes.
     */
    protected function makeModel()
    {
        new MakeModel($this, $this->files);
    }


    /**
     * Generate a Seed
     */
    private function makeSeed()
    {
        new MakeSeed($this, $this->files);
    }



    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the model. (Ex: Post)'],
        ];
    }


    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['schema', 's', InputOption::VALUE_REQUIRED, 'Schema to generate scaffold files. (Ex: --schema="title:string")', null],
            ['form', 'f', InputOption::VALUE_OPTIONAL, 'Use Illumintate/Html Form facade to generate input fields', false],
            ['prefix', 'p', InputOption::VALUE_OPTIONAL, 'Generate schema with prefix', false]
        ];
    }


    /**
     * Make a Controller with default actions
     */
    private function makeController()
    {

        new MakeController($this, $this->files);

    }


    /**
     * Setup views and assets
     *
     */
    private function makeViews()
    {

        foreach ($this->views as $view) {
            // index, create, show, edit
            new MakeView($this, $this->files, $view);
        }


        $this->info('Views created successfully.');

        $this->info('Dump-autoload...');
        $this->composer->dumpAutoloads();

        $this->info('Route::resource("'.$this->getObjName("names").'","'.$this->getObjName("Name").'Controller"); // Add this line in routes.php');

    }


    /**
     * Make a layout.blade.php with bootstrap
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function makeViewLayout()
    {
        new MakeLayout($this, $this->files);
    }


    /**
     * Get access to $meta array
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }


    /**
     * Generate names
     *
     * @param string $config
     * @return mixed
     * @throws \Exception
     */
    public function getObjName($config = 'Name')
    {

        $names = [];
        $args_name = $this->argument('name');


        // Name[0] = Tweet
        $names['Name'] = str_singular(ucfirst($args_name));
        // Name[1] = Tweets
        $names['Names'] = str_plural(ucfirst($args_name));
        // Name[2] = tweets
        $names['names'] = str_plural(strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $args_name)));
        // Name[3] = tweet
        $names['name'] = str_singular(strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $args_name)));


        if (!isset($names[$config])) {
            throw new \Exception("Position name is not found");
        };


        return $names[$config];


    }




}
