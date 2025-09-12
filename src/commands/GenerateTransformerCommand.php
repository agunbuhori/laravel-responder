<?php

namespace Agunbuhori\Responder\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateTransformerCommand extends Command
{
    /**
     * The console command name & signature.
     *
     * @var string
     */
    protected $signature = 'make:responder-transformer {name} {--model=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new Transformer class, optionally bound to an Eloquent model';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $rawName = $this->argument('name');
        $model   = $this->option('model') ? Str::studly($this->option('model')) : null;

        // Normalize class name & sub-namespace
        $className     = Str::studly(class_basename($rawName));
        $subNamespace  = trim(Str::replace('/', '\\', Str::beforeLast($rawName, '/')), '\\');
        $namespace     = 'App\\Http\\Transformers' . ($subNamespace ? "\\{$subNamespace}" : '');

        // File path
        $path = app_path('Http/Transformers/' . str_replace('\\', '/', $rawName) . '.php');

        // Variable name (prefer model if provided, otherwise class base name)
        $baseName = $model ?? $className;
        $variable = Str::camel(preg_replace('/Transformer$/', '', $baseName));

        if (file_exists($path)) {
            $this->error("Transformer already exists: {$path}");
            return;
        }

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $imports   = "use Agunbuhori\\Responder\\Transformer;";
        $signature = "mixed \${$variable}";
        $returnId  = "'id' => \${$variable}['id'] ?? null,";

        if ($model) {
            $imports  .= PHP_EOL . "use App\\Models\\{$model};";
            $signature = "{$model} \${$variable}";
            $returnId  = "'id' => \${$variable}->id,";
        }

        $stub = <<<PHP
        <?php

        namespace {$namespace};

        {$imports}

        class {$className} extends Transformer
        {
            public function transform({$signature}): array
            {
                return [
                    {$returnId}
                ];
            }
        }
        PHP;

        file_put_contents($path, $stub);

        $this->info("Transformer created: {$path}");
    }
}
