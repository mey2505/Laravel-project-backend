<?php
$entities = ['Product', 'Order', 'Review'];

$repoTemplate = "<?php\n\nnamespace App\Repositories;\n\nuse App\Models\{Entity};\n\nclass {Entity}Repository extends BaseRepository\n{\n    public function __construct({Entity} \$model)\n    {\n        parent::__construct(\$model);\n    }\n\n    public function paginate(int \$perPage = 15)\n    {\n        return \$this->model->latest()->paginate(\$perPage);\n    }\n}\n";

$serviceTemplate = "<?php\n\nnamespace App\Services;\n\nuse App\Repositories\{Entity}Repository;\n\nclass {Entity}Service\n{\n    protected {Entity}Repository \$repository;\n\n    public function __construct({Entity}Repository \$repository)\n    {\n        \$this->repository = \$repository;\n    }\n\n    public function getPaginated(int \$perPage = 15)\n    {\n        return \$this->repository->paginate(\$perPage);\n    }\n}\n";

foreach ($entities as $entity) {
    // Generate Repo
    $repoCode = str_replace('{Entity}', $entity, $repoTemplate);
    file_put_contents(__DIR__ . "/app/Repositories/{$entity}Repository.php", $repoCode);

    // Generate Service
    $serviceCode = str_replace('{Entity}', $entity, $serviceTemplate);
    file_put_contents(__DIR__ . "/app/Services/{$entity}Service.php", $serviceCode);

    // Run Artisan commands
    echo shell_exec("php artisan make:controller Admin/{$entity}Controller --resource");
    echo shell_exec("php artisan make:request Admin/Store{$entity}Request");
    echo shell_exec("php artisan make:request Admin/Update{$entity}Request");
}
echo "Scaffolding complete.\n";
