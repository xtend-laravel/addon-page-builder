<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Collection;
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
            'name'        => $this->widgetSlot->name,
            'description' => $this->widgetSlot->description,
            'identifier'  => $this->widgetSlot->identifier,
            'widgets'     => $this->widgetSlot->widgets->map(function (Widget $widget) {
                return [
                    'id'   => $widget->id,
                    'cols' => $widget->cols,
                    'rows' => $widget->rows,
                    'type' => $widget->type,
                    'data' => $widget->setHidden(['pivot', 'updated_at', 'created_at', 'type', 'id'])->toArray(),
                ];
            })->toArray(),
        ]);
    }

    public function getFormSchema(): array
    {
        return [
            TextInput::make('name'),
            Textarea::make('description'),
            TextInput::make('identifier')->disabled(),

            Builder::make('widgets')
                ->blocks([
                    Builder\Block::make(WidgetType::Advertisement->value)
                        ->schema([
                            ...ComponentWidget::defaultSchema(WidgetType::Advertisement),
                            ...ComponentWidget::componentSchema(WidgetType::Advertisement),
                        ])->columns(4),
                    Builder\Block::make(WidgetType::Content->value)
                        ->schema([
                            ...ComponentWidget::defaultSchema(WidgetType::Content),
                            ...ComponentWidget::componentSchema(WidgetType::Content),
                        ])->columns(4),
                    Builder\Block::make(WidgetType::Collection->value)
                        ->schema([
                            ...ComponentWidget::defaultSchema(WidgetType::Collection),
                            ...ComponentWidget::componentSchema(WidgetType::Collection),
                        ])->columns(4)
                ])
                ->cloneable()
                ->collapsible()
                //->collapsed()
        ];
    }

    public function submit()
    {
        $this->widgetSlot->forceFill($this->form->getStateOnly(['description', 'identifier']))->save();
        $widgets = collect($this->form->getState()['widgets']);

        $this->removeDeletedWidgets($widgets);
        $this->createNewWidgets($widgets);
        $this->widgetSlot->refresh();

        $widgets->filter(fn($widget) => array_key_exists('id', $widget))->each(function ($widget) {
            $widgetModel = $this->widgetSlot->widgets()->find($widget['id']);
            $prepareData = array_merge(['type' => $widget['type']], $widget['data']);

            $widgetModel->fill($prepareData)->save();
            $this->widgetSlot->widgets()->updateExistingPivot($widget['id'], [
                'slot_cols' => $widgetModel->cols,
                'slot_rows' => $widgetModel->rows,
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
        $widgets->each(function ($widget) {
            if (!array_key_exists('id', $widget)) {
                $prepareData = array_merge(['type' => $widget['type']], $widget['data']);
                if (empty($prepareData['name']) || empty($prepareData['component'])) {
                    return;
                }
                $widgetModel = $this->widgetSlot->widgets()->create($prepareData);
                $this->widgetSlot->widgets()->updateExistingPivot($widgetModel->id, [
                    'slot_cols' => $widgetModel->cols,
                    'slot_rows' => $widgetModel->rows,
                    'position'  => $this->widgetSlot->widgets()->count() + 1,
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
