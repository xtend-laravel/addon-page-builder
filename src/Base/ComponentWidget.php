<?php

namespace XtendLunar\Addons\PageBuilder\Base;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;
use XtendLunar\Addons\PageBuilder\Enums\WidgetType;

class ComponentWidget
{
    public static function defaultSchema(WidgetType $widgetType): array
    {
        $components = config('xtend-lunar-page-builder.components')[strtolower($widgetType->value)] ?? [];
        $range = collect(range(1, 12))->mapWithKeys(fn ($value) => [$value => $value]);

        return [
            TextInput::make('name')->columnSpan(2),
            Select::make('component')->options(
                collect($components)->flatMap(fn ($component) => [
                    $widgetType->value.class_basename($component) => class_basename($component),
                ])->toArray(),
            )->columnSpan(2)->reactive(),
            Select::make('cols')->options($range)->default(12)->columnSpan(1),
            Select::make('rows')->options($range)->default(1)->columnSpan(1),
            Select::make('col_start')->options($range)->columnSpan(1),
            Select::make('row_start')->options($range)->columnSpan(1),
        ];
    }

    public static function componentSchema(WidgetType $widgetType): array
    {
        return [
            Section::make(fn(\Closure $get) => Str::of($get('component'))->after($widgetType->value)->headline())
                ->visible(fn(\Closure $get) => $get('component'))
                ->columnSpanFull()
                ->schema(
                    fn(\Closure $get, array $state) => static::widgetComponentScheme($get('component'), $widgetType),
                ),
            ];
    }

    protected static function widgetComponentScheme(?string $componentName, WidgetType $type): array
    {
        if (!$componentName) {
            return [];
        }

        $widgetNamespace = Str::of(__NAMESPACE__)->replace('Base', 'Components')->value();
        $componentAbstract = $widgetNamespace.'\\'.$type->value.'\\'.Str::of($componentName)->replace($type->value, '')->value();

        if (!class_exists($componentAbstract)) {
            return [];
        }

        /** @var Widget $component */
        $component = resolve($componentAbstract);
        return $component->schema();
    }
}
