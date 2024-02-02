<?php

namespace XtendLunar\Addons\PageBuilder\Fields\Concerns;

use Closure;
use Illuminate\Support\Collection;
use Lunar\Models\Language;

trait WithLanguages
{
    protected bool | Closure | null $translatable = false;

    // set initial state to '' to avoid error
    protected function bootWithLanguages(): void
    {
        foreach ($this->getLanguages() as $language) {
            $this->state[$language->code] = $this->state[$language->code] ?? '';
        }
    }


    public function translatable(bool | Closure | null $translatable = true): static
    {
        $this->translatable = $translatable;

        return $this;
    }

    public function getTranslatable(): bool
    {
        return $this->evaluate($this->translatable);
    }

    public function getDefaultLanguage(): Language
    {
        return $this->evaluate(Language::getDefault());
    }

    public function getLanguages(): Collection
    {
        return $this->evaluate(Language::select(['id', 'name', 'code'])->get());
    }
}
