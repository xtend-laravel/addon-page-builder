<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Xtend\Extensions\Lunar\Core\Models\Widget;
use Xtend\Extensions\Lunar\Core\Models\WidgetSlot;
use XtendLunar\Addons\PageBuilder\Base\ComponentWidget;
use XtendLunar\Addons\PageBuilder\Enums\WidgetType;

class Edit extends Component implements HasForms
{
    use InteractsWithForms;

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
                ->collapsible()
        ];
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
