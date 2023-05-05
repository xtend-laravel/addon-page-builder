<?php

namespace XtendLunar\Addons\PageBuilder\Fields\Concerns;

use Closure;
use Illuminate\Support\Collection;
use Lunar\Models\Language;

trait WithMultiLanguages
{
    protected bool | Closure | null $multiLanguage = false;

    public function multiLanguage(bool | Closure | null $multiLanguage = true): static
    {
        $this->multiLanguage = $multiLanguage;

        return $this;
    }

    public function getMultiLanguage(): bool
    {
        return $this->evaluate($this->multiLanguage);
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
