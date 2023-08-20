<?php

namespace XtendLunar\Addons\PageBuilder\Restify;

use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use XtendLunar\Addons\PageBuilder\Models\FormSubmission;
use XtendLunar\Addons\PageBuilder\Restify\Presenters\FormSubmissionPresenter;
use XtendLunar\Addons\RestifyApi\Restify\Repository;

class FormSubmissionRepository extends Repository
{
    public static string $model = FormSubmission::class;

    public static string $presenter = FormSubmissionPresenter::class;

    public static bool|array $public = true;

    protected static function booting(): void
    {

    }
}
