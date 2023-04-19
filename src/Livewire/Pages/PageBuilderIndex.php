<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\Pages;

use Livewire\Component;
use Lunar\LivewireTables\Components\Columns\TextColumn;
use Xtend\Extensions\Lunar\Core\Models\Widget;

class PageBuilderIndex extends Component
{
    public function render()
    {
        return view('xtend-lunar-page-builder::livewire.page-builder.index')
            ->layout('adminhub::layouts.app');
    }
}
