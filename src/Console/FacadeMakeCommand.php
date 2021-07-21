<?php

namespace YangYiYi\ExtraCommand\Console;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class FacadeMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:facade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Facade class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Facade';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (parent::handle() === false && !$this->option('force')) {
            return false;
        }

        if ($this->option('all')) {
            $this->input->setOption('service', true);
            $this->input->setOption('model', true);
            $this->input->setOption('migration', true);
        }

        if ($this->option('service')) {
            $this->createService();
        }

        if ($this->option('model')) {
            $parameters = [
                'name' => Str::remove($this->type, $this->getNameInput()),
            ];

            if ($this->option('migration')) {
                $parameters['--migration'] = true;
            }

            $this->call('make:model', $parameters);
        }
    }

    protected function createService()
    {
        $service = Str::studly(class_basename($this->argument('name')));

        if (Str::endsWith($service, $this->type)) {
            $service = str_replace($this->type, '', $service);
        }

        $this->call('make:service', array_filter([
            'name'  => "{$service}Service"
        ]));
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        $name = trim($this->argument('name'));

        if (!Str::endsWith($name, $this->type)) {
            $name = $name . $this->type;
        }

        return $name;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/facade.stub');
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
        return class_exists($this->rootNamespace() . config('extra-command.facade.exists') . $rawName);
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . config('extra-command.facade.default');
    }

    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        $class = str_replace($this->getNamespace($name) . '\\', '', $name);

        $stub = str_replace(['DummyTarget', '{{ accessor }}', '{{accessor}}'], Str::camel($class), $stub);

        return $this->replaceNamespace($stub, $name)
            ->replaceClass($stub, $name);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['all', 'a', InputOption::VALUE_NONE, 'Generate a migration, service and model'],
            ['service', 's', InputOption::VALUE_NONE, 'Create a service for the model'],
            ['model', 'm', InputOption::VALUE_NONE, 'Create a new for the model'],
            ['migration', 'g', InputOption::VALUE_NONE, 'Create a new migration file for the model'],
        ];
    }
}
