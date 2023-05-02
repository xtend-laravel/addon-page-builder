<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots;

use Livewire\Component;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use XtendLunar\Addons\PageBuilder\Base\ComponentWidget;
use XtendLunar\Addons\PageBuilder\Models\Widget;
use XtendLunar\Addons\PageBuilder\Models\WidgetSlot;

class CloneSlot extends Component
{
    use Notifies;

    public WidgetSlot $widgetSlot;

    public ComponentWidget $componentWidget;

    public function mount()
    {
        $widgets = $this->widgetSlot->widgets;

        /** @var WidgetSlot $widgetSlot */
        $this->widgetSlot = $this->widgetSlot->replicate()->fill([
            'identifier' => $this->widgetSlot->identifier.'_clone',
            'name' => $this->widgetSlot->name.' (clone)',
        ]);

        $this->widgetSlot->save();
        $widgets->each(function (Widget $widget) {
            $this->widgetSlot->widgets()->attach($widget->id, [
                'slot_cols' => $widget->cols,
                'slot_rows' => $widget->rows,
                'position'  => $this->widgetSlot->widgets()->count() + 1,
                'data'      => $widget->data,
            ]);
        });

        $this->redirect(route('hub.page-builder.widget-slots.edit', ['widgetSlot' => $this->widgetSlot->id]));
    }
}
