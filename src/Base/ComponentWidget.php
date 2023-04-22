<?php

namespace XtendLunar\Addons\PageBuilder\Base;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;
use XtendLunar\Addons\PageBuilder\Concerns\InteractsWithMediaSettings;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;
use XtendLunar\Addons\PageBuilder\Enums\WidgetType;

class ComponentWidget
{
    use InteractsWithMediaSettings;

    public static function defaultSchema(WidgetType $widgetType): array
    {
        $components = config('xtend-lunar-page-builder.components')[strtolower($widgetType->value)] ?? [];

        return [
            TextInput::make('id')->disabled()->hidden(),
            TextInput::make('name')->columnSpan(2),
            Select::make('component')->options(
                collect($components)->flatMap(fn ($component) => [
                    $widgetType->value.class_basename($component) => class_basename($component),
                ])->toArray(),
            )->columnSpan(2)->reactive(),
            Select::make('cols')->options(range(1, 12))->columnSpan(1),
            Select::make('rows')->options(range(1, 12))->columnSpan(1),
            Select::make('col_start')->options(range(1, 12))->columnSpan(1),
            Select::make('row_start')->options(range(1, 12))->columnSpan(1),
        ];
    }

    public static function componentSchema(WidgetType $widgetType): array
    {
        return [
            Section::make('Component Settings')
                //->visible(fn(\Closure $get) => $get('component'))
                ->columnSpanFull()
                ->schema(fn(\Closure $get, array $state) => [
                    ...static::widgetComponentScheme($get('component'), $widgetType)
                ]),
            ];
    }

    protected static function widgetComponentScheme(string $componentName, WidgetType $type): array
    {
        $widgetNamespace = Str::of(__NAMESPACE__)->replace('Base', 'Components')->value();
        $componentAbstract = $widgetNamespace.'\\'.$type->value.'\\'.Str::of($componentName)->replace($type->value, '')->value();

        /** @var Widget $component */
        $component = resolve($componentAbstract);
        return $component->schema();
    }
}
