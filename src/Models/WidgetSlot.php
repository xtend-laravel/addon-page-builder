<?php

namespace XtendLunar\Addons\PageBuilder\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Lunar\Base\Traits\HasTranslations;

class WidgetSlot extends Model
{
    use HasTranslations;

    protected $table = 'xtend_builder_widget_slots';

    protected $fillable = [
        'name',
        'description',
        'identifier',
        'enabled',
        'params',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'seo_image',
    ];

    protected $casts = [
        'params' => 'array',
        'seo_title' => AsCollection::class,
        'seo_description' => AsCollection::class,
        'seo_keywords' => AsCollection::class,
    ];

    public function page(): HasOne
    {
        return $this->hasOne(
            related: CmsPage::class,
            foreignKey: 'widget_slot_id',
        );
    }

    public function widgets(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Widget::class,
            table: 'xtend_builder_widget_slot_item',
            foreignPivotKey: 'widget_slot_id',
            relatedPivotKey: 'widget_id',
        )->withPivot([
            'slot_cols',
            'slot_rows',
            'position',
            'data',
        ])->orderByPivot('position');
    }
}
