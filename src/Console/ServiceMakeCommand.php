<?php

namespace YangYiYi\ExtraCommand\Console;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class ServiceMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Service class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // First we need to ensure that the given name is not a reserved word within the PHP
        // language and that the class name will actually be valid. If it is not valid we
        // can error now and prevent from polluting the filesystem using invalid files.
        if ($this->isReservedName($this->getNameInput())) {
            $this->error('The name "' . $this->getNameInput() . '" is reserved by PHP.');

            return false;
        }

        $model = $this->getNameInput();
        $name = $this->qualifyClass($model);

        if (!Str::endsWith($name, 'Service')) {
            $name = $name . 'Service';
        }

        $path = $this->getPath($name);

        // Next, We will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ((!$this->hasOption('force') ||
                !$this->option('force')) &&
            $this->alreadyExists($this->getNameInput())
        ) {
            $this->error($this->type . ' already exists!');

            return false;
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $this->files->put($path, $this->sortImports($this->buildClass($name)));

        $this->info($this->type . ' created successfully.');

        if ($this->option('facade')) {
            $this->createFacade();
        }

        if ($this->option('model')) {
            $parameters = [
                'name' => Str::remove('Service', $model),
            ];

            if ($this->option('migration')) {
                $parameters = [
                    'name' => Str::remove('Service', $model),
                    '--migration' => true
                ];
            }

            $this->call('make:model', $parameters);
        }
    }

    protected function createFacade()
    {
        $facade = Str::studly(class_basename($this->argument('name')));

        if (Str::endsWith($facade, 'Service')) {
            $facade = str_replace('Service', '', $facade);
        }

        $this->call('make:facade', array_filter([
            'name'  => "{$facade}Facade"
        ]));
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/service.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    /**
     * Determine if the class already exists.
     *
     * @param  string  $rawName
     * @return bool
     */
    protected function alreadyExists($rawName)
    {

        return class_exists($this->rootNamespace() . config('extra-command.service.exists') . $rawName);
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . config('extra-command.service.default');
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['facade', 'f', InputOption::VALUE_NONE, 'Create a facade for the model'],
            ['model', 'm', InputOption::VALUE_NONE, 'Create a new for the model'],
            ['migration', 'g', InputOption::VALUE_NONE, 'Create a new migration file for the model'],
        ];
    }
}
