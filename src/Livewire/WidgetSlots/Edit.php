<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Lunar\Models\Product;
use Xtend\Extensions\Lunar\Core\Models\Widget;
use Xtend\Extensions\Lunar\Core\Models\WidgetSlot;
use XtendLunar\Addons\PageBuilder\Enums\WidgetType;
use XtendLunar\Addons\PageBuilder\Fields\Image;
use XtendLunar\Addons\PageBuilder\Fields\Text;
use Filament\Forms\Components\Select;

class Edit extends Component implements HasForms
{
    use InteractsWithForms;

    public WidgetSlot $widgetSlot;

    public function mount()
    {
        $this->form->fill([
            'name'        => $this->widgetSlot->name,
            'description' => $this->widgetSlot->description,
            'identifier'  => $this->widgetSlot->identifier,
            'widgets'     => $this->widgetSlot->widgets->map(function (Widget $widget) {
                return [
                    'type' => $widget->type,
                    'data' => $widget->setHidden(['pivot', 'updated_at', 'created_at', 'type'])->toArray()
                ];
            })->toArray(),
        ]);
    }

    public function getFormSchema(): array
    {
        return [
            TextInput::make('name'),
            Textarea::make('description'),
            TextInput::make('identifier'),

            Builder::make('widgets')
                ->blocks([
                    Builder\Block::make(WidgetType::Advertisement->value)
                        ->schema([
                            TextInput::make('id')->disabled(),
                            TextInput::make('name'),
                            TextInput::make('component'),
                            TextInput::make('cols'),
                            TextInput::make('rows'),
                            $this->settingsDataForm(WidgetType::Advertisement),
                        ]),
                    Builder\Block::make(WidgetType::Content->value)
                        ->schema([
                            TextInput::make('id')->disabled(),
                            TextInput::make('name'),
                            TextInput::make('component'),
                            TextInput::make('cols'),
                            TextInput::make('rows'),
                            $this->settingsDataForm(WidgetType::Content),
                        ]),
                    Builder\Block::make(WidgetType::Collection->value)
                        ->schema([
                            TextInput::make('id')->disabled(),
                            TextInput::make('name'),
                            TextInput::make('component'),
                            TextInput::make('cols'),
                            TextInput::make('rows'),
                            $this->settingsDataForm(WidgetType::Collection),
                        ])
                ])->collapsible()->collapsed()
        ];
    }

    protected function settingsDataForm(WidgetType $type): Fieldset
    {
        return match ($type) {
            WidgetType::Advertisement => $this->advertisementFieldset(),
            WidgetType::Content => $this->contentFieldset(),
            WidgetType::Collection => $this->collectionFieldset(),
        };
    }

    protected function advertisementFieldset(): Fieldset
    {
        return Fieldset::make('Data')
            ->schema([
                TextInput::make('data.title')->columnSpan(2),
                Textarea::make('data.description')->columnSpan(2),
//                Image::make('data.image')->columnSpan(2),
                TextInput::make('data.image'),
                Image::make('data.upload_image')
                    ->afterStateUpdated(function (\Closure $set, $state) {
                        $set('data.image', $state->temporaryUrl());
                    })->imagePreviewHeight(100),
                TextInput::make('data.cta')->label('Call to action text')->columnSpan(1),
                TextInput::make('data.route')->label('Url')->columnSpan(1),
                TextInput::make('data.placement'),
            ])->columns(2);
    }

    protected function contentFieldset(): Fieldset
    {
        return Fieldset::make('Settings content')
            ->schema([
                TextInput::make('data.heading'),
                TextInput::make('data.sub_heading'),
            ]);
    }

    protected function collectionFieldset(): Fieldset
    {
        return Fieldset::make('Settings collection')
            ->schema([
                Toggle::make('params.gallery.enable')->inline(false)->columnSpanFull(),
                TextInput::make('params.gallery.layout'),
                Select::make('params.sort')->options(['created_at' => 'Created at', 'updated_at' => 'Updated at']),
                Select::make('params.order')->options(['asc' => 'Ascending', 'desc' => 'Descending']),
                TextInput::make('params.limit'),
                Select::make('params.collection_id')
                    ->options(\Xtend\Extensions\Lunar\Core\Models\Collection::where('type', 'category')
                        ->get()
                        ->mapWithKeys(fn($collection) => [$collection->id => $collection->translateAttribute('name')]))
                    ->label('Collection')
//                    ->searchable()
                ,
            ]);
    }

    public function submit()
    {
        $this->widgetSlot->forceFill($this->form->getStateONly(['description', 'identifier']))->save();

        foreach ($this->form->getState()['widgets'] as $attributes) {
            if ($attributes['data']['id']) {
                $this->widgetSlot->widgets()->find($attributes['data']['id'])->forceFill($attributes['data'])->update();
            } else {
                $this->widgetSlot->widgets()->forceCreate($attributes['data']);
            }
        }
    }

    public function render()
    {
        return view('xtend-lunar-page-builder::livewire.widget-slots.edit')
            ->layout('adminhub::layouts.app');
    }
}
