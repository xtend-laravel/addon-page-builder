<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\Posts;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Str;
use Livewire\Component;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Models\Language;
use XtendLunar\Addons\PageBuilder\Fields\RichEditor;
use XtendLunar\Addons\PageBuilder\Fields\TextArea as TextAreaTranslatable;
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
            'seo_keywords' => $this->post->seo_keywords,
            'seo_image' => $this->post->seo_image,
            'blog_category_id' => $this->post->blog_category_id,
            'status' => $this->post->status ?? 'draft',
        ] : ['status' => 'draft'];

        $translatableState = $this->setRichAreaTranslatableState([
            'excerpt',
            'content',
            'seo_title',
            'seo_description',
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
            Forms\Components\Tabs::make('Post')
                ->tabs([
                    Tabs\Tab::make('General')
                        ->schema($this->getPostFormSchema()),
                    Tabs\Tab::make('SEO')
                        ->schema($this->getSeoFormSchema()),
                ]),
        ];
    }

    protected function getPostFormSchema(): array
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

                    $this->contentFormComponent()
                        ->columnSpan(2),

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

    protected function getSeoFormSchema()
    {
        return [
            Forms\Components\Card::make()
                ->schema([
                    TextInput::make('seo_title')
                        ->label('SEO Meta Title')
                        ->translatable(),
                    TextAreaTranslatable::make('seo_description')
                            ->label('SEO Meta Description')
                            ->translatable(),
                    TagsInput::make('seo_keywords')
                        ->label('SEO Meta Keywords'),
                    FileUpload::make('seo_image')
                        ->visibility('private')
                        ->directory('cms/images')
                        ->preserveFilenames(),
                ]),
        ];
    }

    protected function contentFormComponent()
    {
        $languages = Language::all();

        $tabs = Forms\Components\Tabs::make('Content')
            ->schema(function () use ($languages) {
                return $languages->map(function (Language $language) {
                    return Forms\Components\Tabs\Tab::make(strtoupper($language->code))
                        ->schema([
                            RichEditor::make('content.' . $language->code)->disableLabel(),
                        ]);
                })->toArray();
            });

        return $tabs;
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
