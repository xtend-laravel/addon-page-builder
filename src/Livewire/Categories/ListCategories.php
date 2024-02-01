<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\Categories;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;
use Stephenjude\FilamentBlog\Models\Category;

class ListCategories extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery()
    {
        return Category::query();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->label(__('filament-blog::filament-blog.name'))
                ->searchable()
                ->sortable(),
            TextColumn::make('slug')
                ->label(__('filament-blog::filament-blog.slug'))
                ->searchable()
                ->sortable(),
            IconColumn::make('is_visible')
                ->label(__('filament-blog::filament-blog.visibility')),
            TextColumn::make('updated_at')
                ->label(__('filament-blog::filament-blog.last_updated'))
                ->date(),
        ];
    }

    public function render()
    {
        return view('xtend-lunar-page-builder::livewire.categories.index')
            ->layout('adminhub::layouts.app')
            ;
    }
}