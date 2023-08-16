<?php

namespace XtendLunar\Addons\PageBuilder\Base;

use Filament\Forms\Components\Fieldset;
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
        return [
            TextInput::make('name')->columnSpan(2),
            Select::make('component')
                ->label($widgetType->value . ' Component')
                ->options(
                collect($components)->flatMap(fn ($component) => [
                    $widgetType->value.class_basename($component) => class_basename($component),
                ])->toArray(),
            )->columnSpan(2)->required()->reactive(),
                ...static::gridSchema($widgetType),
        ];
    }

    public static function gridSchema(WidgetType $widgetType): array
    {
        $range = collect(range(1, 12))->mapWithKeys(fn ($value) => [$value => $value]);
        return [
            Fieldset::make('Grid System')
                ->hidden(fn(\Closure $get) => static::componentWithoutGrid($get('component'), $widgetType))
                ->columns(4)
                ->schema([
                    Select::make('cols')->options($range)->default(12)->columnSpan(1),
                    Select::make('rows')->options($range)->default(1)->columnSpan(1),
                    Select::make('col_start')->options($range)->columnSpan(1),
                    Select::make('row_start')->options($range)->columnSpan(1),
                ]),
        ];
    }

    public static function componentWithoutGrid(?string $componentName, WidgetType $widgetType): bool
    {
        $widgetNamespace = Str::of(__NAMESPACE__)->replace('Base', 'Components')->value();
        $componentAbstract = $widgetNamespace.'\\'.$widgetType->value.'\\'.Str::of($componentName)->replace($widgetType->value, '')->value();

        if (!class_exists($componentAbstract)) {
            return false;
        }

        return $componentAbstract::$withoutGridSystem ?? false;
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

    protected static function widgetComponentScheme(?string $componentName, WidgetType $widgetType): array
    {
        if (!$componentName) {
            return [];
        }

        $widgetNamespace = Str::of(__NAMESPACE__)->replace('Base', 'Components')->value();
        $componentAbstract = $widgetNamespace.'\\'.$widgetType->value.'\\'.Str::of($componentName)->replace($widgetType->value, '')->value();

        if (!class_exists($componentAbstract)) {
            return [];
        }

        /** @var Widget $component */
        $component = resolve($componentAbstract);
        return $component->schema();
    }
}
