<?php

namespace XtendLunar\Addons\PageBuilder\Livewire\Widgets\Tables;

use Lunar\LivewireTables\Components\Columns\TextColumn;
use Lunar\LivewireTables\Components\Table;
use Lunar\LivewireTables\Support\TableBuilder;
use Xtend\Extensions\Lunar\Core\Models\Widget;

/**
 * @property TableBuilder $tableBuilder
 */
class WidgetsTable extends Table
{
    public function build()
    {
        $this->tableBuilder->addColumns([
            TextColumn::make('id'),
            TextColumn::make('name'),
            TextColumn::make('type'),
            TextColumn::make('component'),
        ]);
    }

    public function getData()
    {
        return Widget::paginate();
    }
}
