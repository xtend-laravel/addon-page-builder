<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Xtend\Extensions\Lunar\Core\Models\WidgetSlot;
use XtendLunar\Addons\PageBuilder\Enums\WidgetType;

class Edit extends Component implements HasForms
{
    use InteractsWithForms;

    public WidgetSlot $widgetSlot;

    public function mount()
    {
        $this->form->fill([
            'name' => $this->widgetSlot->name,
            'description' => $this->widgetSlot->description,
            'identifier' => $this->widgetSlot->identifier,
        ]);
    }

    public function getFormSchema(): array
    {
        return [
            TextInput::make('name'),
            Textarea::make('description'),
            TextInput::make('identifier'),

            Builder::make('Widgets')
            ->blocks([
                Builder\Block::make(WidgetType::Advertisement->value)
                    ->schema([
                        TextInput::make('name'),
                        TextInput::make('component'),
                        TextInput::make('cols'),
                        TextInput::make('rows'),
                        Textarea::make('data'),
                    ]),
            ])
        ];
    }

    public function submit()
    {
        dd($this->form->getState());
    }

    public function render()
    {
        return view('xtend-lunar-page-builder::livewire.widget-slots.edit')
            ->layout('adminhub::layouts.app');
    }
}
