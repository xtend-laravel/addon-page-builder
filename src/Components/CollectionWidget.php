<?php

namespace XtendLunar\Addons\PageBuilder\Components;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Xtend\Extensions\Lunar\Core\Models\Collection;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;

abstract class CollectionWidget implements Widget
{
    public function schema(): array
    {
        return [
            Fieldset::make('Settings collection')
                ->schema([
                    Toggle::make('params.gallery.enable')->inline(false)->columnSpanFull(),
                    TextInput::make('params.gallery.layout'),
                    Select::make('params.sort')->options(['created_at' => 'Created at', 'updated_at' => 'Updated at']),
                    Select::make('params.order')->options(['asc' => 'Ascending', 'desc' => 'Descending']),
                    TextInput::make('params.limit'),
                    Select::make('params.collection_id')
                        ->options(Collection::where('type', 'category')
                            ->get()
                            ->mapWithKeys(fn($collection) => [$collection->id => $collection->translateAttribute('name')]))
                        ->label('Collection')
                        ->searchable(),
                ]),
        ];
    }
}
