<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use XtendLunar\Addons\PageBuilder\Models\WidgetSlot;

class Create extends Component implements HasForms
{
    use InteractsWithForms;
    use Notifies;

    public WidgetSlot $widgetSlot;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function getFormSchema(): array
    {
        return [
            TextInput::make('identifier')->required()->unique('xtend_builder_widget_slots'),
            TextInput::make('name')->required(),
            Textarea::make('description'),
        ];
    }

    public function submit()
    {
        $widgetSlot = WidgetSlot::create($this->form->getState());

        $this->notify($widgetSlot->name.' widget slot created');
    }

    public function render()
    {
        return view('xtend-lunar-page-builder::livewire.widget-slots.create')
            ->layout('adminhub::layouts.app');
    }
}
