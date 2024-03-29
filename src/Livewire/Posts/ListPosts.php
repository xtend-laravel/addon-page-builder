<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\Posts;

use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;
use Filament\Tables;
use XtendLunar\Addons\PageBuilder\Models\BlogPost as Post;

class ListPosts extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery()
    {
        return Post::query();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\ImageColumn::make('banner')->disk('do'),
            Tables\Columns\TextColumn::make('title')
                ->getStateUsing(fn($record) => $record->translate('title'))
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('category.name')
                ->getStateUsing(fn($record) => $record->category?->translate('name'))
                ->searchable()
                ->sortable(),
            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'secondary',
                    'success' => 'published'
                ])
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\EditAction::make()->url(fn($record) => route('hub.content.posts.edit', $record)),
            Tables\Actions\DeleteAction::make()->requiresConfirmation(),
        ];
    }

    public function render()
    {
        return view('xtend-lunar-page-builder::livewire.posts.index')
            ->layout('adminhub::layouts.app');
    }
}
