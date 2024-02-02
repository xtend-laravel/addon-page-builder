<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\Posts;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Str;
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
        ] : ['status' => 'draft'];

        $translatableState = $this->setRichAreaTranslatableState([
            'excerpt',
            'content',
        ]);

        $state = array_merge($state, $translatableState);

        $this->form->fill($state);
    }

    protected function getFormModel(): BlogPost|string
    {
        return $this->post ?? BlogPost::class;
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
                        ->required()
                        ->translatable(),

                    RichEditor::make('excerpt')
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
                        ->relationship('category', 'name->en'),

                    Forms\Components\Select::make('status')
                        ->default('draft')
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
        $state = $this->form->getState();
        $state['slug'] = Str::slug($state['title']['en']);

        if ($this->post) {
            $this->post->update($state);
        } else {
            $this->post = BlogPost::create($state);
        }

        if ($this->post->wasRecentlyCreated) {
            $this->notify($this->post->translate('title') . ' post created');
        } else {
            $this->notify($this->post->translate('title') . ' post updated');
        }

        $this->redirect(route('hub.content.posts.index'));
    }

    public function render()
    {
        return view('xtend-lunar-page-builder::livewire.posts.form')
            ->layout('adminhub::layouts.app');
    }
}
