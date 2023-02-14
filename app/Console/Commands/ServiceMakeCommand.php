<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class ServiceMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name} {--R|repository}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model service';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';

    /**
     * The name of class being generated.
     *
     * @var string
     */
    private $serviceClass;

    /**
     * The name of class being generated.
     *
     * @var string
     */
    private $service;

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        $this->setServiceClass();

        $path = base_path('app/Services/' . $this->serviceClass . '.php');

        if ($this->alreadyExists($this->getNameInput() . $this->type)) {
            $this->error($this->type . ' already exists!');

            return false;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildClass($this->serviceClass));

        $this->info($this->type.' created successfully.');

        $this->line("<info>Created Service :</info> $this->serviceClass");

        $this->checkOptions();
    }

    /**
     * Set service class name
     *
     * @return  void
     */
    private function setServiceClass()
    {
        $name = $this->argument('name');

        $this->service = $name;

        $this->serviceClass = $name . 'Service';

        return $this;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        if(!$this->argument('name')){
            throw new InvalidArgumentException('Missing required argument model name');
        }

        $stub = parent::replaceClass($stub, $name);

        return str_replace('DummyModel', $this->service, $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return base_path('stubs/Service.stub');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Services';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the service class.'],
        ];
    }

    /**
     * Check if should generate a repository.
     */
    private function checkOptions()
    {
        if ($this->option('repository')) {
            Artisan::call('make:repository', [
                'name' => $this->service,
            ]);

            $this->line('<info>Created Repository :</info> ' . $this->service . 'Repository');
        }
    }
}
