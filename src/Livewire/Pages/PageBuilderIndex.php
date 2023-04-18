<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\Pages;

use Livewire\Component;

class PageBuilderIndex extends Component
{
    public function render()
    {
        return view('xtend-lunar-page-builder::livewire.index')
            ->layout('adminhub::layouts.app');
    }
}
