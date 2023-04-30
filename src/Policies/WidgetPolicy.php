<?php

namespace XtendLunar\Addons\PageBuilder\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use XtendLunar\Addons\PageBuilder\Models\Widget;

class WidgetPolicy
{
    use HandlesAuthorization;

    public function allowRestify(User $user = null): bool
    {
        return true;
    }

    public function show(User $user = null, Widget $model): bool
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

    public function update(User $user, Widget $model): bool
    {
        return false;
    }

    public function updateBulk(User $user, Widget $model): bool
    {
        return false;
    }

    public function deleteBulk(User $user, Widget $model): bool
    {
        return false;
    }

    public function delete(User $user, Widget $model): bool
    {
        return false;
    }
}
