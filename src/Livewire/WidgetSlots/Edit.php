<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Xtend\Extensions\Lunar\Core\Models\Collection;
use Xtend\Extensions\Lunar\Core\Models\Widget;
use Xtend\Extensions\Lunar\Core\Models\WidgetSlot;
use XtendLunar\Addons\PageBuilder\Enums\WidgetType;
use XtendLunar\Addons\PageBuilder\Fields\Image;
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

    protected function widgetSchema(WidgetType $widgetType): array
    {
        $components = config('xtend-lunar-page-builder.components')[strtolower($widgetType->value)] ?? [];

        return [
            TextInput::make('id')->disabled()->hidden(),
            TextInput::make('name')->columnSpan(2),
            Select::make('component')->options(
                collect($components)->flatMap(fn ($component) => [
                    $widgetType->value.class_basename($component) => class_basename($component),
                ])->toArray(),
            )->columnSpan(2),
            Select::make('cols')->options(range(1, 12))->columnSpan(1),
            Select::make('rows')->options(range(1, 12))->columnSpan(1),
            Select::make('col_start')->options(range(1, 12))->columnSpan(1),
            Select::make('row_start')->options(range(1, 12))->columnSpan(1),
        ];
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
                            ...$this->widgetSchema(WidgetType::Advertisement),
                            $this->settingsDataForm(WidgetType::Advertisement)
                        ])->columns(4),
                    Builder\Block::make(WidgetType::Content->value)
                        ->schema([
                            ...$this->widgetSchema(WidgetType::Content),
                            $this->settingsDataForm(WidgetType::Content)
                        ])->columns(4),
                    Builder\Block::make(WidgetType::Collection->value)
                        ->schema([
                            ...$this->widgetSchema(WidgetType::Collection),
                            $this->settingsDataForm(WidgetType::Collection),
                        ])->columns(4)
                ])
                ->collapsible()
//                ->collapsed()
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
                Section::make('Media')
                    ->schema([
                        Placeholder::make('media_type_label')->label('Select media type')->columnSpan(2),
                        Radio::make('data.media_type')
                            ->disableLabel()
                            ->hint('Select the type of media to display')
                            ->inline()
                            ->reactive()
                            ->options([
                                'image_url' => 'Image URL',
                                'image_upload' => 'Image upload',
                                'video_embed' => 'Video embed',
                            ]),
                        TextInput::make('data.image')
                            ->hidden(fn(\Closure $get) => $get('data.media_type') !== 'image_url')
                            ->columnSpan(2),
                        Image::make('upload_image')->imagePreviewHeight(100)
                            ->hidden(fn(\Closure $get) => $get('data.media_type') !== 'image_upload')
                            ->columnSpan(2),
                        TextInput::make('data.video')
                            ->hidden(fn(\Closure $get) => $get('data.media_type') !== 'video_embed')
                            ->columnSpan(2),
                ]),
                TextInput::make('data.cta')->label('Call to action text')->columnSpan(1),
                TextInput::make('data.route')->label('Url')->columnSpan(1),
                TextInput::make('data.placement')->hidden(),
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
                    ->options(Collection::where('type', 'category')
                        ->get()
                        ->mapWithKeys(fn($collection) => [$collection->id => $collection->translateAttribute('name')]))
                    ->label('Collection')
//                    ->searchable()
                ,
            ]);
    }

    public function submit()
    {
        //dd($this->form->getState());
        $this->widgetSlot->forceFill($this->form->getStateOnly(['description', 'identifier']))->save();

        $widgetIds = [];

        foreach ($this->form->getState()['widgets'] as ['type' => $type, 'data' => $data]) {
            $attributes = Arr::except($data + ['type' => $type], 'upload_image');

            if ($type === WidgetType::Advertisement->value) {
                $tmpImage = Arr::first($data['upload_image']);
                if ($tmpImage instanceof TemporaryUploadedFile) {
                    $path = $tmpImage->storePublicly('page-builder');
                    $attributes['data']['image'] = $url = Storage::url($path);
                }
            }

            Widget::unguard();

            $widget = Widget::query()->updateOrCreate(['id' => $data['id']], $attributes);

            $widgetIds[] = $widget->id;
        }

        $this->widgetSlot->widgets()->sync($widgetIds);
    }

    public function render()
    {
        return view('xtend-lunar-page-builder::livewire.widget-slots.edit')
            ->layout('adminhub::layouts.app');
    }
}
