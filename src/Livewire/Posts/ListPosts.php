<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\Posts;

use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;
use Stephenjude\FilamentBlog\Models\Post;
use Filament\Tables;

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
            Tables\Columns\ImageColumn::make('banner')
                ->label(__('filament-blog::filament-blog.banner'))
                ->rounded(),
            Tables\Columns\TextColumn::make('title')
                ->label(__('filament-blog::filament-blog.title'))
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('author.name')
                ->label(__('filament-blog::filament-blog.author_name'))
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('category.name')
                ->label(__('filament-blog::filament-blog.category_name'))
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('published_at')
                ->label(__('filament-blog::filament-blog.published_at'))
                ->date()
                ->sortable(),
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