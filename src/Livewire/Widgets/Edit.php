<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\Widgets;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Xtend\Extensions\Lunar\Core\Models\Widget;

class Edit extends Component implements HasForms
{
    use InteractsWithForms;

    public Widget $widget;

    public function mount()
    {
        dd($this->widget->toArray());
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name'),
        ];
    }

    public function submit()
    {
    }

    public function render()
    {
        return view('xtend-lunar-page-builder::livewire.widgets.edit')
            ->layout('adminhub::layouts.app');
    }
}
