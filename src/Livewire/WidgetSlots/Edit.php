<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use XtendLunar\Addons\PageBuilder\Base\ComponentWidget;
use XtendLunar\Addons\PageBuilder\Enums\WidgetType;
use XtendLunar\Addons\PageBuilder\Models\Widget;
use XtendLunar\Addons\PageBuilder\Models\WidgetSlot;

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
        $this->form->fill([
            'identifier'  => Str::of($this->widgetSlot->identifier)->replace('_clone', '')->value(),
            'type'        => $this->widgetSlot->type,
            'name'        => Str::of($this->widgetSlot->name)->replace(' (clone)', '')->value(),
            'language_id' => $this->widgetSlot->language_id,
            'description' => $this->widgetSlot->description,
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
        ]);
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
            Card::make()->hidden(fn(\Closure $get) => $get('type') !== 'cms')->schema([
                RichEditor::make('content'),
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
                            ->label(fn(\Closure $get, $state) => ($state['name'] ?? WidgetType::Advertisement->value).' ('.($state['component'] ?? 'No Component Set').')')
                            ->schema([
                                ...ComponentWidget::defaultSchema(WidgetType::Advertisement),
                                ...ComponentWidget::componentSchema(WidgetType::Advertisement),
                            ])->columns(4),
                        Builder\Block::make(WidgetType::Content->value)
                            ->label(fn(\Closure $get, $state) => ($state['name'] ?? WidgetType::Content->value).' ('.($state['component'] ?? 'No Component Set').')')
                            ->schema([
                                ...ComponentWidget::defaultSchema(WidgetType::Content),
                                ...ComponentWidget::componentSchema(WidgetType::Content),
                            ])->columns(4),
                        Builder\Block::make(WidgetType::Collection->value)
                            ->label(fn(\Closure $get, $state) => ($state['name'] ?? WidgetType::Collection->value).' ('.($state['component'] ?? 'No Component Set').')')
                            ->schema([
                                ...ComponentWidget::defaultSchema(WidgetType::Collection),
                                ...ComponentWidget::componentSchema(WidgetType::Collection),
                            ])->columns(4)
                    ])
                    ->cloneable()
                    ->collapsible()
                    ->reorderableWithButtons()
                    //->collapsed()
            ]),
        ];
    }

    public function submit()
    {
        $this->widgetSlot->forceFill($this->form->getStateOnly(['identifier', 'type', 'name', 'language_id', 'description']))->save();
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
                if (empty($prepareData['name']) || empty($prepareData['component'])) {
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
