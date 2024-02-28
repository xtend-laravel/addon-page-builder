<?php

namespace XtendLunar\Addons\PageBuilder\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Lunar\Base\Traits\HasTranslations;

class CmsPage extends Pivot
{
    use HasTranslations;

    protected $table = 'xtend_builder_cms_page';

    protected $casts = [
        'heading' => AsCollection::class,
        'content' => AsCollection::class,
        'seo_title' => AsCollection::class,
        'seo_description' => AsCollection::class,
        'seo_keywords' => AsCollection::class,
    ];

    protected $fillable = [
        'widget_slot_id',
        'image_upload',
        'heading',
        'content',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'seo_image',
    ];

    public function widgetSlot(): BelongsTo
    {
        return $this->belongsTo(WidgetSlot::class, 'widget_slot_id');
    }
}
