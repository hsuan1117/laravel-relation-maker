<?php

namespace Hsuan\LaravelRelationMaker\Commands;

use Carbon\Carbon;
use Illuminate\Console\GeneratorCommand;

class LaravelRelationMakerCommand extends GeneratorCommand
{
    protected $signature = 'make:relation {name} {relation?} {modelB?} {--m|migration}';
    protected $name = 'make:relation';
    protected $description = 'Make relation';

    public function handle()
    {
        $modelA = $this->argument('name');
        $modelB = $this->argument('modelB');
        $relation = $this->argument('relation');

        $this->info('   > Checking ' . $this->qualifyModel($modelA));
        if (file_exists($this->getPath($this->qualifyClass($modelA)))) {
            // 代表是把原本有的東東新增
            if (method_exists($this->qualifyClass($modelA), strtolower($modelB)) || method_exists($this->qualifyClass($modelA), strtolower($modelB) . 's')) {
                $this->error('Method exists.');
                return 1;
            }
            $content = file_get_contents($this->getPath($this->qualifyClass($modelA)));
            $str = $this->str_lreplace('}', (<<<FUNC
                public function {{ modelName }}(){
                    return \$this->{{ relation }}({{ model }}::class);
                }
            }
            FUNC), $content);
            file_put_contents($this->getPath($this->qualifyClass($modelA)), $str);
            $this->info('   # Modified ' . $this->qualifyClass($modelA));
        } else {
            parent::handle();
            $this->info('   # Created ' . $this->qualifyClass($modelA));
        }

        $content = file_get_contents($this->getPath($this->qualifyClass($modelA)));

        $str = str_replace('{{ model }}', $modelB, $content);

        if ($relation === 'hasMany') {
            $str = str_replace('{{ modelName }}', strtolower($modelB) . 's', $str);
            $str = str_replace('{{ relation }}', $relation, $str);
            file_put_contents($this->getPath($this->qualifyClass($modelA)), $str);

            if (!file_exists($this->getPath($this->qualifyClass($modelB)))) {
                $this->call('make:relation', [
                    'name' => $modelB,
                    'relation' => 'belongsTo',
                    'modelB' => $modelA,
                    '--migration' => $this->option('migration')
                ]);
            }
        } else if ($relation === 'belongsTo') {
            $str = str_replace('{{ modelName }}', strtolower($modelB), $str);
            $str = str_replace('{{ relation }}', $relation, $str);
            file_put_contents($this->getPath($this->qualifyClass($modelA)), $str);

            if (!file_exists($this->getPath($this->qualifyClass($modelB)))) {
                $this->call('make:relation', [
                    'name' => $modelB,
                    'relation' => 'hasMany',
                    'modelB' => $modelA,
                    '--migration' => $this->option('migration')
                ]);
            }
        } else if ($relation === 'belongsToMany') {
            $str = str_replace('{{ modelName }}', strtolower($modelB) . 's', $str);
            $str = str_replace('{{ relation }}', $relation, $str);
            file_put_contents($this->getPath($this->qualifyClass($modelA)), $str);

            if (!file_exists($this->getPath($this->qualifyClass($modelB)))) {
                $this->call('make:relation', [
                    'name' => $modelB,
                    'relation' => 'belongsToMany',
                    'modelB' => $modelA,
                    '--migration' => $this->option('migration')
                ]);
            }
        }

        if ($this->option('migration')) {
            if ($relation === 'belongsTo') {
                //$this->info('belongsTo');
                # One to Many
                $file = file_get_contents(__DIR__ . '/stubs/migration.relation.stub');
                $this->clearMigration();
                $this->addFieldMigration('bigInteger',strtolower($modelB).'_id');
                $file = str_replace('{{ class }}', 'Create' . $modelA . 'Table', $file);
                $file = str_replace('{{ table }}', strtolower($modelA) . 's', $file);
                $file = str_replace('{{ tableField }}', $this->databaseMigration, $file);
                file_put_contents(database_path('migrations') . '/' . Carbon::now()->format('Y_m_d_His_') . 'create'. '_' . strtolower($modelA) . '_table.php', $file);
                $this->info('Created Migration for '.$modelA);
            } else if ($relation === 'hasMany') {
                //$this->info('hasMany');
                # One to Many
                $file = file_get_contents(__DIR__ . '/stubs/migration.relation.stub');
                $this->clearMigration();
                $file = str_replace('{{ class }}', 'Create' . $modelA . 'Table', $file);
                $file = str_replace('{{ table }}', strtolower($modelA) . 's', $file);
                $file = str_replace('{{ tableField }}', $this->databaseMigration, $file);
                file_put_contents(database_path('migrations') . '/' . Carbon::now()->format('Y_m_d_His_') . 'create' . '_' . strtolower($modelA) . '_table.php', $file);
                $this->info('Created Migration for '.$modelA);
            } else if ($relation === 'belongsToMany') {
                # Many to Many
                if ($modelA < $modelB) {
                    ############
                    ## Normal ##
                    ############
                    $arr = [$modelA,$modelB];
                    foreach ($arr as $model) {
                        $file = file_get_contents(__DIR__ . '/stubs/migration.relation.stub');
                        $this->clearMigration();
                        $file = str_replace('{{ class }}', 'Create' . $model . 'Table', $file);
                        $file = str_replace('{{ table }}', strtolower($model) . 's', $file);
                        $file = str_replace('{{ tableField }}', $this->databaseMigration, $file);
                        file_put_contents(database_path('migrations') . '/' . Carbon::now()->format('Y_m_d_His_') . 'create' . '_' . strtolower($model) . '_table.php', $file);
                        $this->info('Created Migration for ' . $model);
                    }
                    ##############
                    ## Relation ##
                    ##############
                    $file = file_get_contents(__DIR__ . '/stubs/migration.relation.stub');
                    $this->clearMigration();
                    $this->addFieldMigration('bigInteger', strtolower($modelA) . '_id');
                    $this->addFieldMigration('bigInteger', strtolower($modelB) . '_id');

                    $file = str_replace('{{ class }}', 'Create' . $modelA . $modelB . 'Table', $file);
                    $file = str_replace('{{ table }}', strtolower($modelA) . '_' . strtolower($modelB), $file);
                    $file = str_replace('{{ tableField }}', $this->databaseMigration, $file);
                    file_put_contents(database_path('migrations') . '/' . Carbon::now()->format('Y_m_d_His_') . 'create' . '_' . strtolower($modelA) . '_' . strtolower($modelB) . '_table.php', $file);
                    $this->info('Created Relationship Migration for '.$modelA.$modelB);
                }
            }
        }

        return 0;
    }

    protected $databaseMigration = '';

    protected function clearMigration()
    {
        $this->databaseMigration = '';
    }

    protected function addFieldMigration($type, $column)
    {
        $this->databaseMigration .= <<<Field
        \$table->$type('$column');

        Field;
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return 'App\\' . 'Models';
    }

    function str_lreplace($search, $replace, $subject)
    {
        $pos = strrpos($subject, $search);

        if ($pos !== false) {
            $subject = substr_replace($subject, $replace, $pos, strlen($search));
        }

        return $subject;
    }

    /*
        protected function getFileGenerationPath()
        {
            $path = $this->getPathByOptionOrConfig('path', 'seed_target_path');
            $tableName = $this->getTableName();

            return "{$path}/{$tableName}TableSeeder.php";
        }

        protected function getPathByOptionOrConfig($option, $configName)
        {
            if ($path = $this->option($option)) return $path;

            return Config::get("generators::config.{$configName}");
        }*/

    protected function getStub()
    {
        return __DIR__ . '/stubs/model.relation.stub';
    }
}
