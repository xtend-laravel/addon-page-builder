<?php

namespace XtendLunar\Addons\PageBuilder\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Composer;

class CreateComponentWidget extends Command
{
    protected $signature = 'addon-page-builder:create-component-widget
        {name : The name of the component widget}
        {--force : Overwrite existing files}';

    protected $description = 'Create a new component widget';

    public function __construct(
        protected Filesystem $filesystem,
        protected Composer $composer)
    {
        parent::__construct();
    }

    public function handle(PackageManifest $packageManifest): int
    {
        $this->packageManifest = $packageManifest;

        return self::SUCCESS;
    }
}
