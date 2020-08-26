<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    private const REPOSITORY_INTERFACE_DIR = 'Repositories' . DIRECTORY_SEPARATOR . 'Interfaces';

    private const REPOSITORY_NAMESPACE = 'App\Repositories';

    public function register()
    {
        $this->scanDir(base_path('src' . DIRECTORY_SEPARATOR . static::REPOSITORY_INTERFACE_DIR), '');
    }

    /**
     *
     * @param  string  $path
     * @param  string  $nameSpace
     */
    private function scanDir(string $path, string $nameSpace): void
    {
        foreach (new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS) as $file) {
            $fileName = $file->getBasename('Interface.php');
            if ($file->isFile()) {
                $this->bind($nameSpace . '\\' . $fileName);
            }
            if ($file->isDir()) {
                $this->scanDir($path . DIRECTORY_SEPARATOR . $fileName, $nameSpace . '\\' . $fileName);
            }
        }
    }

    /**
     *
     * @param  string  $nameSpace
     */
    private function bind(string $nameSpace): void
    {
        $this->app->bind(
            static::REPOSITORY_NAMESPACE . '\Interfaces' . $nameSpace . 'Interface',
            static::REPOSITORY_NAMESPACE . $nameSpace);
    }
}
