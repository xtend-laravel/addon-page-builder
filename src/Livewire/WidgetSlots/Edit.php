<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Models\Language;
use XtendLunar\Addons\PageBuilder\Base\ComponentWidget;
use XtendLunar\Addons\PageBuilder\Enums\WidgetType;
use XtendLunar\Addons\PageBuilder\Fields\RichEditor;
use XtendLunar\Addons\PageBuilder\Fields\TextInput;
use XtendLunar\Addons\PageBuilder\Fields\TextArea as TextAreaTranslatable;
use XtendLunar\Addons\PageBuilder\Models\Widget;
use XtendLunar\Addons\PageBuilder\Models\WidgetSlot;
use XtendLunar\Features\FormBuilder\Fields\Tags;

class Edit extends Component implements HasForms
{
    use InteractsWithForms;
    use Notifies;

    public WidgetSlot $widgetSlot;

    public ComponentWidget $componentWidget;

    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    public function mount()
    {
        $mountType = $this->widgetSlot->type === 'cms'
            ? $this->mountCms()
            : $this->mountBuilder();

        $this->form->fill([
            'identifier'  => Str::of($this->widgetSlot->identifier)->replace('_clone', '')->value(),
            'type'        => $this->widgetSlot->type,
            'name'        => Str::of($this->widgetSlot->name)->replace(' (clone)', '')->value(),
            'language_id' => $this->widgetSlot->language_id,
            ...$mountType,
        ]);
    }

    protected function mountBuilder(): array
    {
        return [
            'description' => $this->widgetSlot->description,
            'seo_title' => $this->widgetSlot->seo_title,
            'seo_description' => $this->widgetSlot->seo_description,
            'seo_image' => $this->widgetSlot->seo_image,
            'widgets'     => $this->widgetSlot->widgets->map(function (Widget $widget) {
                $pivotData = json_decode($widget->pivot->data, true);
                $data = $widget->setHidden(['pivot', 'updated_at', 'created_at', 'type', 'id'])->toArray();
                $data['data'] = $pivotData ?? $data['data'];
                return [
                    'id'   => $widget->id,
                    'cols' => $widget->cols,
                    'rows' => $widget->rows,
                    'type' => $widget->type,
                    'data' => $data,
                ];
            })->toArray(),
        ];
    }

    protected function mountCms(): array
    {
        /** @var \XtendLunar\Addons\PageBuilder\Models\CmsPage $page */
        $page = $this->widgetSlot->page;
        $content = Language::all()->mapWithKeys(fn(Language $language) => [
            'content.' . $language->code => $page?->translate('content', $language->code),
        ])->toArray();

        return [
            'image_upload' => $this->widgetSlot->page?->image_upload,
            'heading' => $page?->heading,
            'seo_title' => $page?->seo_title,
            'seo_description' => $page?->seo_description,
            'seo_image' => $page?->seo_image,
            ...$content,
        ];
    }

    public function getFormSchema(): array
    {
        return [
            TextInput::make('identifier')
                ->formatStateUsing(function (\Closure $get) {
                    $slug = Str::slug($get('name'), '_');
                    return $slug;
                })
                ->disabled()
                ->helperText('Unique identifier for this widget slot to map to the front-end (Note this will soon be replaced by CMS page dropdown)'),
            Select::make('type')
                ->options([
                    'builder' => 'Builder',
                    'cms'     => 'CMS',
                ])
                ->reactive()
                ->helperText('Type of widget slot'),
            TextInput::make('name')
                ->reactive()
                ->afterStateUpdated(function (\Closure $get, \Closure $set) {
                    $slug = Str::slug($get('name'), '_');
                    $set('identifier', $slug);
                })
                ->required(),
            ...$this->cmsSchema(),
            ...$this->builderSchema(),
        ];
    }

    protected function cmsSchema(): array
    {
        return [
            Card::make()
                ->hidden(fn(\Closure $get) => $get('type') !== 'cms')->schema([
                    FileUpload::make('image_upload')
                        ->visibility('private')
                        ->directory('cms/images')
                        ->preserveFilenames(),
                    TextInput::make('heading')
                        ->label('Heading')
                        ->translatable(),
                ]),
            Section::make('content')
                ->heading('Page Content')
                ->hidden(fn(\Closure $get) => $get('type') !== 'cms')
                ->schema([
                    ...Language::all()->map(function (Language $language) {
                        return RichEditor::make('content.' . $language->code)
                            ->label($language->name);
                    })->toArray(),
                ]),
            Section::make('seo')
                ->heading('SEO Meta')
                ->schema([
                    TextInput::make('seo_title')
                        ->label('SEO Meta Title')
                        ->translatable(),
                    TextAreaTranslatable::make('seo_description')
                            ->label('SEO Meta Description')
                            ->translatable(),
                    FileUpload::make('seo_image')
                        ->visibility('private')
                        ->directory('cms/images')
                        ->preserveFilenames(),
                ]),
        ];
    }

    protected function builderSchema(): array
    {
        return [
            Card::make()->hidden(fn(\Closure $get) => $get('type') !== 'builder')->schema([
                Textarea::make('description'),
                Section::make('A/B Testing')->schema([
                    Toggle::make('enable_testing')
                        ->reactive()
                        ->label('Enable A/B Testing'),
                    Radio::make('version')
                        ->inline()
                        ->disableLabel()
                        ->hidden(fn(\Closure $get) => !$get('enable_testing'))
                        ->options([
                            'A' => 'Version A',
                            'B' => 'Version B',
                        ]),
                ]),
                Builder::make('widgets')
                    ->blocks([
                        Builder\Block::make(WidgetType::Advertisement->value)
                            ->label(fn(\Closure $get, $state) => $this->setWidgetLabel(WidgetType::Advertisement, $state))
                            ->schema([
                                ...ComponentWidget::defaultSchema(WidgetType::Advertisement),
                                ...ComponentWidget::componentSchema(WidgetType::Advertisement),
                            ])->columns(4),
                        Builder\Block::make(WidgetType::Content->value)
                            ->label(fn(\Closure $get, $state) => $this->setWidgetLabel(WidgetType::Content, $state))
                            ->schema([
                                ...ComponentWidget::defaultSchema(WidgetType::Content),
                                ...ComponentWidget::componentSchema(WidgetType::Content),
                            ])->columns(4),
                        Builder\Block::make(WidgetType::Collection->value)
                            ->label(fn(\Closure $get, $state) => $this->setWidgetLabel(WidgetType::Collection, $state))
                            ->schema([
                                ...ComponentWidget::defaultSchema(WidgetType::Collection),
                                ...ComponentWidget::componentSchema(WidgetType::Collection),
                            ])->columns(4),
                        Builder\Block::make(WidgetType::Form->value)
                            ->label(fn(\Closure $get, $state) => $this->setWidgetLabel(WidgetType::Form, $state))
                            ->schema([
                                ...ComponentWidget::defaultSchema(WidgetType::Form),
                                ...ComponentWidget::componentSchema(WidgetType::Form),
                            ])->columns(4)
                    ])
                    ->cloneable()
                    ->collapsible()
                    ->reorderableWithButtons()
                    //->collapsed()
            ]),
        ];
    }

    protected function setWidgetLabel(WidgetType $widgetType, array $state): string
    {
        $label = $state['name'] ?? $widgetType->value;
        $component = $state['component'] ?? null;
        if ($component) {
            $label .= ' ('.$component.')';
        }
        return $label;
    }

    public function submit()
    {
        $this->widgetSlot->forceFill($this->form->getStateOnly([
            'identifier',
            'type',
            'name',
            'language_id',
            'description',
            'seo_title',
            'seo_description',
            'seo_image',
        ]))->save();

        $this->form->getState()['type'] === 'cms'
            ? $this->saveCms()
            : $this->saveBuilder();
    }

    protected function saveCms(): void
    {
        $this->widgetSlot->page()->updateOrCreate([
            'widget_slot_id' => $this->widgetSlot->id,
        ], [
            'image_upload' => $this->form->getState()['image_upload'],
            'seo_title' => $this->form->getState()['seo_title'],
            'seo_description' => $this->form->getState()['seo_description'],
            'seo_image' => $this->form->getState()['seo_image'],
            'heading' => $this->form->getState()['heading'],
            'content' => $this->form->getState()['content'],
        ]);

        $this->notify($this->widgetSlot->name.' cms page updated');
    }

    protected function saveBuilder(): void
    {
        $widgets = collect($this->form->getState()['widgets']);

        $this->removeDeletedWidgets($widgets);
        $this->createNewWidgets($widgets);
        $this->widgetSlot->refresh();

        $widgets->filter(fn($widget) => array_key_exists('id', $widget))->each(function ($widget, $index) {
            $widgetModel = $this->widgetSlot->widgets()->find($widget['id']);
            $prepareData = array_merge(['type' => $widget['type']], $widget['data']);

            $position = $index + 1;
            $this->widgetSlot->widgets()->where('position', '>=', $position)->increment('position');
            $widgetModel->fill(Arr::except($prepareData, 'data'))->save();
            $this->widgetSlot->widgets()->updateExistingPivot($widget['id'], [
                'slot_cols' => $widgetModel->cols,
                'slot_rows' => $widgetModel->rows,
                'data'      => $prepareData['data'] ?? null,
                'position'  => $position,
            ]);
        });

        $this->notify($this->widgetSlot->name.' widgets updated');
    }

    protected function removeDeletedWidgets(Collection $widgets): void
    {
        $widgetIdsToRemove = $this->widgetSlot->widgets()->pluck('widget_id')->diff($widgets->pluck('id')->filter())->values();
        $this->widgetSlot->widgets()->find($widgetIdsToRemove)->each(function (Widget $widget) {
            $this->widgetSlot->widgets()->detach($widget->id);
            $widget->delete();
        });
    }

    protected function createNewWidgets(Collection $widgets): void
    {
        // @todo Positions are not correct when adding new widgets to the slot (they are added to the end of the list)
        $widgets->each(function ($widget, $index) {
            if (!array_key_exists('id', $widget)) {
                $prepareData = array_merge(['type' => $widget['type']], $widget['data']);
                $prepareData['name'] ??= $prepareData['component'];
                if (empty($prepareData['component'])) {
                    return;
                }
                $position = $index + 1;
                $widgetModel = $this->widgetSlot->widgets()->create($prepareData);
                $this->widgetSlot->widgets()->where('position', '>=', $position)->increment('position');
                $this->widgetSlot->widgets()->updateExistingPivot($widgetModel->id, [
                    'slot_cols' => $widgetModel->cols,
                    'slot_rows' => $widgetModel->rows,
                    'data'      => $prepareData['data'] ?? null,
                    'position'  => $position,
                ]);
            }
        });
    }

    public function render()
    {
        return view('xtend-lunar-page-builder::livewire.widget-slots.edit')
            ->layout('adminhub::layouts.app');
    }
}
