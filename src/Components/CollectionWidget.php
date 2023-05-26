<?php

namespace XtendLunar\Addons\PageBuilder\Components;

use Filament\Forms\Components\Card;
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
                ->columns(4)
                ->schema([
                    Toggle::make('params.collection.newest_auto')
                        ->label('Newest Auto Collection')
                        ->inline(false)
                        ->reactive()
                        ->columnSpanFull(),
                    //TextInput::make('params.gallery.layout'),
                    Card::make()->schema([
                        Select::make('params.sort')->options(['created_at' => 'Created at', 'updated_at' => 'Updated at'])->columnSpan(1),
                        Select::make('params.order')->options(['asc' => 'Ascending', 'desc' => 'Descending'])->columnSpan(1),
                    ])->columnSpanFull(),
                    TextInput::make('params.limit')->columnSpan(2),
                    Select::make('params.collection_id')
                        ->hidden(fn(\Closure $get) => $get('params.collection.newest_auto'))
                        ->options(Collection::all()->mapWithKeys(
                            fn($collection) => [$collection->id => $collection->translateAttribute('name').' ('.$collection->id.')'])
                        )
                        ->label('Collection')
                        ->multiple()
                        ->searchable()
                        ->columnSpan(2),
                ]),
        ];
    }
}
