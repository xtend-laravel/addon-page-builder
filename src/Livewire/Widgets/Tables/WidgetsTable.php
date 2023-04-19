<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\Widgets\Tables;

use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Component;
use Xtend\Extensions\Lunar\Core\Models\Widget;

class WidgetsTable extends Component implements HasTable
{
    use InteractsWithTable;

    public function getTableQuery(): Builder|Relation
    {
        return Widget::query();
    }

    public function getTableColumns(): array
    {
        return [
            TextColumn::make('id'),
            TextColumn::make('name'),
            TextColumn::make('type'),
            TextColumn::make('component'),
            ToggleColumn::make('enabled'),
        ];
    }

    public function getTableActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }

    public function render()
    {
        return view('adminhub::livewire.components.tables.base-table')
            ->layout('adminhub::layouts.base');
    }
}
