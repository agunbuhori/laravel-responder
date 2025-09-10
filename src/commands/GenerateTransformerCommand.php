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
        $name = Str::studly($this->argument('name'));
        $model = $this->option('model') ? Str::studly($this->option('model')) : null;

        $className = $name;

        $variable = Str::camel($model ?? $name);

        $namespace = 'App\\Http\\Transformers';

        $path = app_path("Http/Transformers/{$className}.php");

        if (file_exists($path)) {
            $this->error("Transformer already exists: {$path}");
            return;
        }

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $imports = "use Agunbuhori\\Responder\\Transformer;";
        $signature = "mixed \${$variable}";
        $returnId = "'id' => \${$variable}['id'] ?? null,";

        if ($model) {
            $imports .= PHP_EOL . "use App\\Models\\{$model};";
            $signature = "{$model} \${$variable}";
            $returnId = "'id' => \${$variable}->id,";
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
