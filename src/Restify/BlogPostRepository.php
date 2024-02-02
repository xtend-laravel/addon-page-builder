<?php

namespace XtendLunar\Addons\PageBuilder\Restify;

use Illuminate\Support\Str;
use XtendLunar\Addons\PageBuilder\Models\BlogPost;
use XtendLunar\Addons\PageBuilder\Restify\Presenters\BlogPostPresenter;
use XtendLunar\Addons\RestifyApi\Restify\Repository;

class BlogPostRepository extends Repository
{
    public static string $model = BlogPost::class;

    public static string $presenter = BlogPostPresenter::class;

    public static bool|array $public = true;

    protected static function booting(): void
    {
        $identifier = Str::slug(request()->route()->parameter('repositoryId'), '_');
        $blogPost = BlogPost::query()->firstWhere('slug', $identifier);

        if ($blogPost) {
            request()->route()->setParameter('repositoryId', $blogPost->id);
        }
    }
}
