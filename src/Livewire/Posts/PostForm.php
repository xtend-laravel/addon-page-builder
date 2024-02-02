<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\Posts;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Filament\Forms;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Models\Language;
use XtendLunar\Addons\PageBuilder\Fields\RichEditor;
use XtendLunar\Addons\PageBuilder\Fields\TextArea;
use XtendLunar\Addons\PageBuilder\Fields\TextInput;
use XtendLunar\Addons\PageBuilder\Models\BlogPost;

class PostForm extends Component implements HasForms
{
    use InteractsWithForms;
    use Notifies;

    public ?BlogPost $post = null;

    public function mount()
    {
        $state = $this->post ? [
            'slug' => $this->post->slug,
            'title' => $this->post->title,
            'banner' => $this->post->banner,
            'blog_category_id' => $this->post->blog_category_id,
            'status' => $this->post->status ?? 'draft',
        ] : [];

        $translatableState = $this->setRichAreaTranslatableState([
            'excerpt',
            'content',
        ]);

        $state = array_merge($state, $translatableState);

        $this->form->fill($state);
    }

    protected function getFormModel(): BlogPost
    {
        return $this->post ?? new BlogPost();
    }

    protected function setRichAreaTranslatableState(array $fields): array
    {
        return collect($fields)->mapWithKeys(function ($field) {
            return Language::all()->mapWithKeys(fn(Language $language) => [
                $field . '.' . $language->code => $this->post?->translate($field, $language->code),
            ])->toArray();
        })->toArray();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Card::make()
                ->schema([
                    TextInput::make('title')
                        //->required()
                        //->reactive()
                        ->translatable(),
                        //->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state))),

                    TextInput::make('slug')
                        ->disabled()
                        //->required()
                        ->unique(BlogPost::class, 'slug', fn($record) => $record),

                    RichEditor::make('content')
                        // ->minLength(50)
                        // ->maxLength(1000)
                        ->translatable()
                        ->disableToolbarButtons([
                            'attachFiles',
                            'codeBlock',
                        ])
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

                    RichEditor::make('content')
                        ->label(__('Content'))
                        ->required()
                        ->translatable()
                        ->disableToolbarButtons([
                            'attachFiles',
                            'codeBlock',
                        ])
                        ->columnSpan([
                            'sm' => 2,
                        ]),

                    Forms\Components\Select::make('category_id')
                        ->relationship('category', 'name'),

                    Forms\Components\Select::make('status')
                        ->options([
                            'draft'     => __('Draft'),
                            'published' => __('Published'),
                        ]),
                ])
                ->columns([
                    'sm' => 2,
                ])
                ->columnSpan(2),
        ];
    }

    public function submit(): void
    {
        dd($this->form->getState());
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
