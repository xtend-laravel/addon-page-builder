<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\Widgets;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Xtend\Extensions\Lunar\Core\Models\Widget;
use XtendLunar\Addons\PageBuilder\Fields\Text;

class Edit extends Component implements HasForms
{
    use InteractsWithForms;

    public Widget $widget;

    public $name;

    public function mount()
    {
        $this->form->fill([
            'name' => $this->widget->name,
            'type' => $this->widget->type,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name'),
            TextInput::make('type')->disabled(),
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
