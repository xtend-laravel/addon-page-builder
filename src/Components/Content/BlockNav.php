<?php

namespace XtendLunar\Addons\PageBuilder\Components\Content;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;
use Lunar\Models\Collection;
use XtendLunar\Addons\PageBuilder\Components\ContentWidget;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;

class BlockNav extends ContentWidget implements Widget
{
    public function schema(): array
    {
        return [
            Repeater::make('data.nav')
                ->maxItems(6)
                ->disableLabel()
                ->defaultItems(1)
                ->itemLabel(fn (\Closure $get, array $state, Repeater $component): ?string => 'Item #')
                ->createItemButtonLabel('Add Item')
                ->schema([
                    TextInput::make('name')->required(),
                    Select::make('route')->options(Collection::all()->flatMap(fn (Collection $collection) => [
                        $this->getRouteFromCollection($collection) => $collection->translateAttribute('name'),
                    ])->toArray())->required(),
                ]),
        ];
    }

    protected function getRouteFromCollection(Collection $collection): string
    {
        return Str::of($collection->translateAttribute('name'))->slug()->prepend($collection->id.'-')->value();
    }
}
