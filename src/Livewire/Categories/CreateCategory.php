<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\Categories;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Str;
use Livewire\Component;
use Stephenjude\FilamentBlog\Models\Category;
use Filament\Forms;
use Stephenjude\FilamentBlog\Traits\HasContentEditor;

/**
 * @property Form $form
 */
class CreateCategory extends Component implements HasForms
{
    use InteractsWithForms;
    use HasContentEditor;

    public function mount()
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('filament-blog::filament-blog.name'))
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                    Forms\Components\TextInput::make('slug')
                        ->label(__('filament-blog::filament-blog.slug'))
                        ->disabled()
                        ->required()
                        ->unique(Category::class, 'slug', fn ($record) => $record),
                    self::getContentEditor('description'),
                    Forms\Components\Toggle::make('is_visible')
                        ->label(__('filament-blog::filament-blog.visible_to_guests'))
                        ->default(true),
                ])
                ->columns([
                    'sm' => 2,
                ])
                ->columnSpan(2),
            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\Placeholder::make('created_at')
                        ->label(__('filament-blog::filament-blog.created_at'))
                        ->content(fn (?Category $record): string => $record ? $record->created_at->diffForHumans() : '-'),
                    Forms\Components\Placeholder::make('updated_at')
                        ->label(__('filament-blog::filament-blog.last_modified_at'))
                        ->content(fn (?Category $record): string => $record ? $record->updated_at->diffForHumans() : '-'),
                ])
                ->columnSpan(1),
        ];
    }

    public function render()
    {
        return view('xtend-lunar-page-builder::livewire.categories.create')
            ->layout('adminhub::layouts.app');
    }
}