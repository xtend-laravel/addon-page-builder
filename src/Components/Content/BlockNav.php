<?php

namespace XtendLunar\Addons\PageBuilder\Components\Content;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Illuminate\Support\Str;
use Lunar\Models\Collection;
use XtendLunar\Addons\PageBuilder\Components\ContentWidget;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;
use XtendLunar\Addons\PageBuilder\Fields\TextArea;
use XtendLunar\Addons\PageBuilder\Fields\TextInput;

class BlockNav extends ContentWidget implements Widget
{
    public function schema(): array
    {
        return [
            Select::make('data.nav-direction')->options([
                'horizontal' => 'Horizontal',
                'vertical' => 'Vertical',
            ])->required()->reactive(),
            TextArea::make('data.before-content')
                ->translatable()
                ->columnSpan(2),
            Repeater::make('data.nav')
                ->hidden(fn(\Closure $get): bool => empty($get('data.nav-direction')))
                ->maxItems(6)
                ->disableLabel()
                ->defaultItems(1)
                ->itemLabel(fn (\Closure $get, array $state, Repeater $component): ?string => 'Item #')
                ->createItemButtonLabel('Add Item')
                ->schema([
                    TextInput::make('name')->translatable()->required(),
                    Select::make('route')->options(Collection::all()->flatMap(fn (Collection $collection) => [
                        $this->getRouteFromCollection($collection) => $collection->translateAttribute('name'),
                    ])->toArray())->required(),
                ]),
            TextArea::make('data.after-content')
                ->translatable()
                ->columnSpan(2),
        ];
    }

    protected function getRouteFromCollection(Collection $collection): string
    {
        return Str::of($collection->translateAttribute('name'))->slug()->prepend($collection->id.'-')->value();
    }
}
