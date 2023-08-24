<?php

namespace XtendLunar\Addons\PageBuilder\Restify;

use Binaryk\LaravelRestify\Http\Requests\RepositoryStoreRequest;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use XtendLunar\Addons\PageBuilder\Models\FormSubmission;
use XtendLunar\Addons\PageBuilder\Restify\Presenters\FormSubmissionPresenter;
use XtendLunar\Addons\RestifyApi\Restify\Repository;

class FormSubmissionRepository extends Repository
{
    public static string $model = FormSubmission::class;

    public static string $presenter = FormSubmissionPresenter::class;

    public static bool|array $public = true;

    public static function authorizedToStore(Request $request): bool
    {
        // @todo Need to find out why the gate check is not working
        // dd(Gate::check('store', static::$model));

        return true;
    }
}
