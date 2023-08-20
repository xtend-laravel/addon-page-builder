<?php

namespace XtendLunar\Addons\PageBuilder\Restify;

use Binaryk\LaravelRestify\Fields\BelongsToMany;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use XtendLunar\Addons\PageBuilder\Models\Form;
use XtendLunar\Addons\PageBuilder\Restify\Presenters\FormPresenter;
use XtendLunar\Addons\RestifyApi\Restify\Repository;

class FormRepository extends Repository
{
    public static string $model = Form::class;

    public static string $presenter = FormPresenter::class;

    public static bool|array $public = true;

    public static function related(): array
    {
        return [
            BelongsToMany::make('submissions', FormSubmissionRepository::class),
        ];
    }
}
