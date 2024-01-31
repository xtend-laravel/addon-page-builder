<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\Posts;

use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Str;
use Livewire\Component;
use Stephenjude\FilamentBlog\Models\Post;
use Stephenjude\FilamentBlog\Resources\PostResource;
use Filament\Forms;
use Stephenjude\FilamentBlog\Traits\HasContentEditor;

class CreatePost extends Component implements HasForms
{
    use InteractsWithForms;
    use HasContentEditor;

    public $data;

    protected function getFormStatePath(): ?string
    {
        return 'data';
    }

    protected function getFormSchema()
    {
        return [
            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label(__('Title'))
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                    Forms\Components\TextInput::make('slug')
                        ->label(__('filament-blog::filament-blog.slug'))
                        ->disabled()
                        ->required()
                        ->unique(Post::class, 'slug', fn ($record) => $record),

                    Forms\Components\Textarea::make('excerpt')
                        ->label(__('filament-blog::filament-blog.excerpt'))
                        ->rows(2)
                        ->minLength(50)
                        ->maxLength(1000)
                        ->columnSpan([
                            'sm' => 2,
                        ]),

                    Forms\Components\FileUpload::make('banner')
                        ->label(__('filament-blog::filament-blog.banner'))
                        ->image()
                        ->maxSize(config('filament-blog.banner.maxSize', 5120))
                        ->imageCropAspectRatio(config('filament-blog.banner.cropAspectRatio', '16:9'))
                        ->disk(config('filament-blog.banner.disk', 'public'))
                        ->directory(config('filament-blog.banner.directory', 'blog'))
                        ->columnSpan([
                            'sm' => 2,
                        ]),

                    self::getContentEditor('content'),

                    Forms\Components\BelongsToSelect::make('blog_author_id')
                        ->label(__('filament-blog::filament-blog.author'))
                        ->relationship('author', 'name')
                        ->searchable()
                        ->required(),

                    Forms\Components\BelongsToSelect::make('blog_category_id')
                        ->label(__('filament-blog::filament-blog.category'))
                        ->relationship('category', 'name')
                        ->searchable()
                        ->required(),

                    Forms\Components\DatePicker::make('published_at')
                        ->label(__('filament-blog::filament-blog.published_date')),
                    SpatieTagsInput::make('tags')
                        ->label(__('filament-blog::filament-blog.tags'))
                        ->required(),
                ])
                ->columns([
                    'sm' => 2,
                ])
                ->columnSpan(2),
            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\Placeholder::make('created_at')
                        ->label(__('filament-blog::filament-blog.created_at'))
                        ->content(fn (
                            ?Post $record
                        ): string => $record ? $record->created_at->diffForHumans() : '-'),
                    Forms\Components\Placeholder::make('updated_at')
                        ->label(__('filament-blog::filament-blog.last_modified_at'))
                        ->content(fn (
                            ?Post $record
                        ): string => $record ? $record->updated_at->diffForHumans() : '-'),
                ])
                ->columnSpan(1),
        ];
    }

    public function render()
    {
        return view('xtend-lunar-page-builder::livewire.posts.create')
            ->layout('adminhub::layouts.app')
            ;
    }
}