<?php

namespace XtendLunar\Addons\PageBuilder\Fields\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait InteractsWithModel
{
    protected Model|Builder $model;

    protected mixed $value;

    public function model(string $model, $identifier = null): static
    {
        $this->model = tap(resolve($model), function (Model|Builder $model) use ($identifier) {
            if ($identifier) {
                $model->where($model->getKeyName(), $identifier);
            }
        });

        return $this;
    }

    public function valueUsing(callable $callback): static
    {
        $this->value = $callback($this->model);

        return $this;
    }
}
