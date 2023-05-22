<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\WidgetSlots;

use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Component;
use XtendLunar\Addons\PageBuilder\Models\WidgetSlot;

class Table extends Component implements HasTable
{
    use InteractsWithTable;

    public function getTableQuery(): Builder|Relation
    {
        return WidgetSlot::query();
    }

    public function getTableColumns(): array
    {
        return [
            TextColumn::make('id'),
            TextColumn::make('name'),
            BadgeColumn::make('language.code'),
            TextColumn::make('description'),
            BadgeColumn::make('type')->formatStateUsing(fn($state) => match ($state) {
                'builder' => 'Builder',
                'cms' => 'CMS',
            }),
            ToggleColumn::make('enabled'),
        ];
    }

    public function getTableActions(): array
    {
        return [
            EditAction::make()->url(fn($record) => route('hub.page-builder.widget-slots.edit', $record)),
            Action::make('clone')
                ->icon('heroicon-s-document-duplicate')
                ->label('Clone')
                ->url(fn($record) => route('hub.page-builder.widget-slots.clone', $record)),
            DeleteAction::make()->requiresConfirmation(),
        ];
    }

    public function render()
    {
        return view('adminhub::livewire.components.tables.base-table')
            ->layout('adminhub::layouts.base');
    }
}
