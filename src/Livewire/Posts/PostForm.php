<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\Posts;

use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Str;
use Livewire\Component;
use Filament\Forms;
use Stephenjude\FilamentBlog\Traits\HasContentEditor;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use XtendLunar\Addons\PageBuilder\Models\CmsPost as Post;

class PostForm extends Component implements HasForms
{
    use InteractsWithForms;
    use HasContentEditor;
    use Notifies;

    public Post $post;

    public function mount($post = null)
    {
        $this->post = $post ?? new Post;

        $this->form->fill([
            'title'            => $this->post->title,
            'slug'             => $this->post->slug,
            'excerpt'          => $this->post->excerpt,
            'content'          => $this->post->content,
            'banner'           => $this->post->banner,
            'blog_category_id' => $this->post->blog_category_id,
            'status'           => $this->post->status ?? 'draft',
        ]);
    }

    protected function getFormModel()
    {
        return $this->post;
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state))),

                    Forms\Components\TextInput::make('slug')
                        ->disabled()
                        ->required()
                        ->unique(Post::class, 'slug', fn($record) => $record),

                    Forms\Components\Textarea::make('excerpt')
                        ->rows(2)
                        ->minLength(50)
                        ->maxLength(1000)
                        ->columnSpan([
                            'sm' => 2,
                        ]),

                    Forms\Components\FileUpload::make('banner')
                        ->label(__('Banner'))
                        ->image()
                        ->maxSize(1024 * 5)
                        ->imageCropAspectRatio('16:9')
                        ->directory('cms')
                        ->columnSpan([
                            'sm' => 2,
                        ]),

                    self::getContentEditor('content'),

                    Forms\Components\Select::make('category_id')
                        ->relationship('category', 'name')
                        ->required(),

                    Forms\Components\Select::make('status')
                        ->options([
                            'draft'     => __('Draft'),
                            'published' => __('Published'),
                        ])
                ])
                ->columns([
                    'sm' => 2,
                ])
                ->columnSpan(2),
        ];
    }

    public function submit()
    {
        $state = $this->form->getState();

        $this->post->fill($state)->save();

        if ($this->post->wasRecentlyCreated) {
            $this->notify($state['title'] . ' post created');

            $this->redirect(route('hub.content.posts.edit', $this->post));
        } else {
            $this->notify($state['title'] . ' post updated');
        }
    }

    public function render()
    {
        return view('xtend-lunar-page-builder::livewire.posts.form')
            ->layout('adminhub::layouts.app');
    }
}