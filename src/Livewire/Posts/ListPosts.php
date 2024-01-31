<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\Posts;

use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;
use Stephenjude\FilamentBlog\Models\Post;

class ListPosts extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery()
    {
        return Post::query();
    }

    public function render()
    {
        return view('xtend-lunar-page-builder::livewire.posts.index')
            ->layout('adminhub::layouts.app');
    }
}