<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\Categories;

use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;
use XtendLunar\Addons\PageBuilder\Models\BlogCategory as Category;

class ListCategories extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery()
    {
        return Category::query()->latest('id');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name.en')
                ->searchable()
                ->sortable(),
            TextColumn::make('slug')
                ->searchable()
                ->sortable(),
            IconColumn::make('is_visible')
                ->boolean(),
            TextColumn::make('updated_at')
                ->date(),
        ];
    }

    public function getTableActions(): array
    {
        return [
            EditAction::make()->url(fn($record) => route('hub.content.categories.edit', $record)),
            DeleteAction::make()->requiresConfirmation(),
        ];
    }

    public function render()
    {
        return view('xtend-lunar-page-builder::livewire.categories.index')
            ->layout('adminhub::layouts.app')
            ;
    }
}
