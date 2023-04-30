<?php

namespace XtendLunar\Addons\PageBuilder\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use XtendLunar\Addons\PageBuilder\Models\WidgetSlot;

class WidgetSlotPolicy
{
    use HandlesAuthorization;

    public function allowRestify(User $user = null): bool
    {
        return true;
    }

    public function show(User $user = null, WidgetSlot $model): bool
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

    public function update(User $user, WidgetSlot $model): bool
    {
        return false;
    }

    public function updateBulk(User $user, WidgetSlot $model): bool
    {
        return false;
    }

    public function deleteBulk(User $user, WidgetSlot $model): bool
    {
        return false;
    }

    public function delete(User $user, WidgetSlot $model): bool
    {
        return false;
    }
}
