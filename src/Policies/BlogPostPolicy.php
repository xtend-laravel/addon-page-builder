<?php

namespace XtendLunar\Addons\PageBuilder\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use XtendLunar\Addons\PageBuilder\Models\BlogPost;

class BlogPostPolicy
{
    use HandlesAuthorization;

    public function allowRestify(User $user = null): bool
    {
        return true;
    }

    public function show(User $user = null, BlogPost $model): bool
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

    public function update(User $user, BlogPost $model): bool
    {
        return false;
    }

    public function updateBulk(User $user, BlogPost $model): bool
    {
        return false;
    }

    public function deleteBulk(User $user, BlogPost $model): bool
    {
        return false;
    }

    public function delete(User $user, BlogPost $model): bool
    {
        return false;
    }
}
