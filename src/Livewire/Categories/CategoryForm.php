<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\Categories;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Filament\Forms;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use XtendLunar\Addons\PageBuilder\Fields\RichEditor;
use XtendLunar\Addons\PageBuilder\Fields\TextInput;
use XtendLunar\Addons\PageBuilder\Models\BlogCategory as Category;

class CategoryForm extends Component implements HasForms
{
    use InteractsWithForms;
    use Notifies;

    public Category $category;

    public function mount($category = null)
    {
        $this->category = $category ?? new Category;

        $this->form->fill([
            'name'        => $this->category->name,
            'description' => $this->category->description ?? ['en' => '', 'fr' => '', 'ar' => ''],
            'is_visible'  => $this->category->is_visible ?? true,
            'created_at'  => $this->category->created_at,
            'updated_at'  => $this->category->updated_at,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Card::make()
                ->schema([
                    TextInput::make('name')
                        ->translatable()
                        ->required(),
                    RichEditor::make('description')
                        ->translatable()
                        ->required()
                        ->disableToolbarButtons(['attachFiles'])
                        ->columnSpan(2),
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
            $this->notify($state['name']['en'] . ' category created');
        } else {
            $this->notify($state['name']['en'] . ' category updated');
        }

        $this->redirect(route('hub.content.categories.index'));
    }

    public function render()
    {
        return view('xtend-lunar-page-builder::livewire.categories.form')
            ->layout('adminhub::layouts.app');
    }
}
