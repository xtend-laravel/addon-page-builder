<?php

namespace XtendLunar\Addons\PageBuilder\Fields;

use Filament\Forms\Components\FileUpload;

class Image extends FileUpload
{
    protected function setUp(): void
    {
        $this->image()
            ->directory('page-builder')
            ->maxSize(5 * 1024);
    }
}
