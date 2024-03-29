<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Models\Language;
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
            TextInput::make('identifier')
                ->required()
                ->helperText('Unique identifier for this widget slot to map to the front-end (Note this will soon be replaced by CMS page dropdown)')
                ->unique('xtend_builder_widget_slots'),
            Select::make('type')
                ->options([
                    'builder' => 'Builder',
                    'cms'     => 'CMS',
                ])
                ->helperText('Type of widget slot'),
            TextInput::make('name')->required(),
            Textarea::make('description'),
        ];
    }

    public function submit()
    {
        $widgetSlot = WidgetSlot::create($this->form->getState());

        $this->notify($widgetSlot->name.' widget slot created');

        $this->redirect(route('hub.page-builder.widget-slots.edit', ['widgetSlot' => $widgetSlot->id]));
    }

    public function render()
    {
        return view('xtend-lunar-page-builder::livewire.widget-slots.create')
            ->layout('adminhub::layouts.app');
    }
}
