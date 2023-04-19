<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\Widgets;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Ramsey\Uuid\Fields\SerializableFieldsTrait;
use Xtend\Extensions\Lunar\Core\Models\Widget;
use XtendLunar\Addons\PageBuilder\Enums\WidgetType;
use XtendLunar\Addons\PageBuilder\Fields\Image;
use XtendLunar\Addons\PageBuilder\Fields\Text;

class Edit extends Component implements HasForms
{
    use InteractsWithForms;

    public Widget $widget;

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
            TextInput::make('cols')->numeric()->maxValue(12)->hint('The number of columns this widget should span on a 12 column grid'),
            TextInput::make('rows')->numeric()->maxValue(12)->hint('The number of rows this widget should span on a 12 column grid'),

            $this->settingsDataForm(),
        ];
    }

    protected function settingsDataForm(): Fieldset
    {
        return match ($this->widget->type) {
            WidgetType::Advertisement->value => $this->advertisementFieldset(),
            WidgetType::Content->value => $this->contentFieldset(),
            WidgetType::Collection->value => $this->collectionFieldset(),
        };
    }

    protected function advertisementFieldset(): Fieldset
    {
        return Fieldset::make('Settings data')
            ->schema([
                TextInput::make('data.title')->columnSpan(2),
                Textarea::make('data.description')->columnSpan(2),
                Image::make('data.image')->image()->maxSize(5 * 1024)->directory('page-builder')->columnSpan(2),
                TextInput::make('data.cta')->label('Call to action text')->columnSpan(1),
                TextInput::make('data.route')->label('Url')->columnSpan(1),
                TextInput::make('data.placement'),
            ])->columns(2);
    }

    public function submit()
    {
        dd($this->form->getState());
    }

    public function render()
    {
        return view('xtend-lunar-page-builder::livewire.widgets.edit')
            ->layout('adminhub::layouts.app');
    }

    protected function contentFieldset(): Fieldset
    {
        return Fieldset::make('Settings content');
    }

    protected function collectionFieldset(): Fieldset
    {
        return Fieldset::make('Settings collection');
    }
}
