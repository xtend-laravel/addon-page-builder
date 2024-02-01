<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\Categories;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Str;
use Livewire\Component;
use Stephenjude\FilamentBlog\Models\Category;
use Filament\Forms;
use Stephenjude\FilamentBlog\Traits\HasContentEditor;
use Lunar\Hub\Http\Livewire\Traits\Notifies;

class CategoryForm extends Component implements HasForms
{
    use InteractsWithForms;
    use HasContentEditor;
    use Notifies;

    public Category $category;

    public function mount($category = null)
    {
        $this->category = $category ?? new Category;

        $this->form->fill([
            'name'        => $this->category->name,
            'slug'        => $this->category->slug,
            'description' => $this->category->description,
            'is_visible'  => $this->category->is_visible,
            'created_at'  => $this->category->created_at,
            'updated_at'  => $this->category->updated_at,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state))),
                    Forms\Components\TextInput::make('slug')
                        ->disabled()
                        ->required()
//                        ->unique(Category::class, 'slug', ignorable: $this->category)
                    ,
                    self::getContentEditor('description'),
                    Forms\Components\Toggle::make('is_visible')
                        ->default(true),
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

        $this->category->fill($state)->save();

        if ($this->category->wasRecentlyCreated) {
            $this->notify($state['name'] . ' category created');

            $this->redirect(route('hub.content.categories.edit', $this->category));
        } else {
            $this->notify($state['name'] . ' category updated');
        }

    }


    public function render()
    {
        return view('xtend-lunar-page-builder::livewire.categories.form')
            ->layout('adminhub::layouts.app');
    }
}