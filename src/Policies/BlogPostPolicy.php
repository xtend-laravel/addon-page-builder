<?php

namespace XtendLunar\Addons\PageBuilder\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use XtendLunar\Addons\PageBuilder\Models\CmsPost;

class BlogPostPolicy
{
    use HandlesAuthorization;

    public function allowRestify(User $user = null): bool
    {
        return true;
    }

    public function show(User $user = null, CmsPost $model): bool
    {
        return true;
    }

    public function store(User $user): bool
    {
        return false;
    }

    public function storeBulk(User $user): bool
    {
        return false;
    }

    public function update(User $user, CmsPost $model): bool
    {
        return false;
    }

    public function updateBulk(User $user, CmsPost $model): bool
    {
        return false;
    }

    public function deleteBulk(User $user, CmsPost $model): bool
    {
        return false;
    }

    public function delete(User $user, CmsPost $model): bool
    {
        return false;
    }
}
