<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\Pages;

use Livewire\Component;

class WidgetEdit extends Component
{
    public function render()
    {
        return view('xtend-lunar-page-builder::livewire.widgets.edit')
            ->layout('adminhub::layouts.app');
    }
}
