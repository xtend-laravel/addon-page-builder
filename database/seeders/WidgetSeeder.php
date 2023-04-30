<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Lunar\FieldTypes\Text;
use Lunar\FieldTypes\TranslatedText;
use Lunar\Models\Collection;
use Lunar\Models\CollectionGroup;
use Lunar\Models\Product;
use XtendLunar\Addons\PageBuilder\Models\Widget;
use XtendLunar\Addons\PageBuilder\Models\WidgetSlot;

class WidgetSeeder extends Seeder
{
    protected CollectionGroup $collectionGroup;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->setupFeaturedCollectionGroup();
        $this->setupHomePageSlot();

        // $this->advertisementWidgets();
        // $this->contentWidgets();
        // $this->productWidgets();
    }

    protected function setupFeaturedCollectionGroup(): void
    {
        $this->collectionGroup = CollectionGroup::firstOrCreate([
            'name' => 'Featured',
            'handle' => 'featured',
        ]);
    }

    protected function setupHomePageSlot(): void
    {
        /** @var WidgetSlot $slot */
        $slot = WidgetSlot::query()->updateOrCreate([
            'name' => 'Home Page',
            'description' => 'Home page slot for A/B testing',
            'identifier' => 'page_home',
        ], [
            'params' => [
                'split_testing' => [
                    'page' => 'home',
                    'version' => 'A',
                    'params' => [],
                ]
            ],
        ]);

        collect([
            [
                'name' => 'Featured women\'s clothing banner',
                'type' => 'Advertisement',
                'component' => 'AdvertisementFeaturedOverlay',
                'rows' => 1,
                'cols' => 6,
                'data' => [
                    'placement' => 'home_page_top',
                    'image' => 'https://www.jacques-loup.com/themes/at_movic/assets/img/modules/appagebuilder/images/en-pc-home-mea-femme-pap-diesel23-jacquesloup-16012023-2.png',
                    'title' => 'Clothing',
                    'route' => '/39-clothing',
                    'description' => 'Casual, trendy and denim this summer!',
                    'cta' => 'Discover our selection',
                ],
            ],
            [
                'name' => 'Featured men\'s clothing banner',
                'type' => 'Advertisement',
                'component' => 'AdvertisementFeaturedOverlay',
                'rows' => 1,
                'cols' => 6,
                'data' => [
                    'placement' => 'home_page_top',
                    'image' => 'https://www.jacques-loup.com/themes/at_movic/assets/img/modules/appagebuilder/images/en-pc-home-mea-homme--diesel23-jacquesloup-16012023.png',
                    'title' => 'Ready to wear',
                    'route' => '/11-clothing',
                    'description' => 'Contemporary look in a stylish way',
                    'cta' => 'Discover our selection',
                ],
            ],
            [
                'name' => 'SS23',
                'type' => 'Content',
                'component' => 'ContentHeading',
                'rows' => 1,
                'cols' => 12,
                'data' => [
                    // @todo Need to support multi language later
                    'heading' => 'THE SS23 COLLECTION',
                    'sub_heading' => 'Update your dressing with the latest hits of our favorite luxury brands and designers.',
                ],
            ],
            [
                'name' => 'Collection of SS23 items',
                'type' => 'Collection',
                'component' => 'CollectionFeaturedCarousel',
                'rows' => 1,
                'cols' => 12,
                'params' => [
                    'limit' => 12,
                    'sort' => 'created_at',
                    'order' => 'desc',
                    'collection_id' => $this->getFeaturedCollectionId('SS23'),
                    'carousel' => [
                        'enable' => true,
                        'items' => 6,
                    ],
                ],
            ],
            [
                'name' => 'Spotlight heading',
                'type' => 'Content',
                'component' => 'ContentHeading',
                'rows' => 1,
                'cols' => 12,
                'data' => [
                    // @todo Need to support multi language later
                    'heading' => 'SPOTLIGHT ON OUR FAVORITE DESIGNERS',
                    'sub_heading' => 'Our team has put together a large choice of brands. Find out more about worldwide know brands and also upcoming designers.',
                ],
            ],
            [
                'name' => 'Spotlight designer banner',
                'type' => 'Advertisement',
                'component' => 'AdvertisementSpotlight',
                'rows' => 1,
                'cols' => 4,
                'data' => [
                    'placement' => 'home_page_middle',
                    'image' => 'https://www.jacques-loup.com/themes/at_movic/assets/img/modules/appagebuilder/images/en-pc-home-createur-santoni.png',
                    'title' => 'ITALIAN PERFECTION FOR SHOES',
                    'route' => '/designers/23-santoni',
                    'cta' => 'Enter showroom',
                ],
            ],
            [
                'name' => 'Spotlight designer banner',
                'type' => 'Advertisement',
                'component' => 'AdvertisementSpotlight',
                'rows' => 1,
                'cols' => 4,
                'data' => [
                    'placement' => 'home_page_middle',
                    'image' => 'https://www.jacques-loup.com/themes/at_movic/assets/img/modules/appagebuilder/images/en-pc-home-createur-aquazzura-jacquesloup.png',
                    'title' => 'BEAUTY, STYLE AND ELEGANCE',
                    'route' => '/designers/27-aquazzura',
                    'cta' => 'Enter showroom',
                ],
            ],
            [
                'name' => 'Spotlight designer banner',
                'type' => 'Advertisement',
                'component' => 'AdvertisementSpotlight',
                'rows' => 1,
                'cols' => 4,
                'data' => [
                    'placement' => 'home_page_middle',
                    'image' => 'https://www.jacques-loup.com/themes/at_movic/assets/img/modules/appagebuilder/images/en-pc-home-createur-tods-jacquesloup.png',
                    'title' => 'QUALITY AND DESIGN FOR EVERYONE',
                    'route' => '/designers/15-tods',
                    'cta' => 'Enter showroom',
                ],
            ],
            [
                'name' => 'Best sales heading',
                'type' => 'Content',
                'component' => 'ContentHeading',
                'rows' => 1,
                'cols' => 12,
                'data' => [
                    // @todo Need to support multi language later
                    'heading' => 'OUR BEST SALES',
                    'sub_heading' => 'Get inspired by our most popular articles for your luxury shopping!',
                ],
            ],
            [
                'name' => 'Collection of Best Sales items',
                'type' => 'Collection',
                'component' => 'CollectionFeaturedCarousel',
                'rows' => 1,
                'cols' => 12,
                'params' => [
                    'limit' => 12,
                    'sort' => 'created_at',
                    'order' => 'desc',
                    'collection_id' => $this->getFeaturedCollectionId('Best Sales'),
                    'carousel' => [
                        'enable' => true,
                        'items' => 6,
                    ],
                ],
            ],
            [
                'name' => 'Sneakers collection heading',
                'type' => 'Content',
                'component' => 'ContentHeading',
                'rows' => 1,
                'cols' => 12,
                'data' => [
                    // @todo Need to support multi language later
                    'heading' => 'OUR SNEAKERS SELECTION',
                    'sub_heading' => 'Addicted to the most trendy shoes over the last years? Sneakers are indispensable, useful, comfortable and super stylish. Discover our collection and get a gorgeous style. MSGM, Hogan, Tod\'s, Philippe Model, and many other brands are available online. To go to work, to the gym or to walk, it\'s always the perfect time to wear sneakers.',
                ],
            ],
            [
                'name' => 'Collection of Sneakers',
                'type' => 'Collection',
                'component' => 'CollectionFeaturedGallery',
                'rows' => 3,
                'cols' => 6,
                'params' => [
                    'limit' => 6,
                    'sort' => 'created_at',
                    'order' => 'desc',
                    'collection_id' => $this->getFeaturedCollectionId('Sneakers'),
                    'gallery' => [
                        'enable' => true,
                        'layout' => 'featured_right',
                    ],
                ],
            ],
            [
                'name' => 'Featured men\'s clothing banner',
                'type' => 'Advertisement',
                'component' => 'AdvertisementFeaturedOverlay',
                'rows' => 1,
                'cols' => 6,
                'data' => [
                    'placement' => 'home_page_top',
                    'image' => 'https://www.jacques-loup.com/themes/at_movic/assets/img/modules/appagebuilder/images/en-pc-home-mea-homme--diesel23-jacquesloup-16012023.png',
                    'title' => 'Ready to wear',
                    'route' => '/11-clothing',
                    'description' => 'Contemporary look in a stylish way',
                    'cta' => 'Discover our selection',
                ],
            ],
            [
                'name' => 'Shoe JL collection heading',
                'type' => 'Content',
                'component' => 'ContentHeading',
                'rows' => 1,
                'cols' => 12,
                'data' => [
                    // @todo Need to support multi language later
                    'heading' => 'THE SHOE COLLECTION OF JACQUES LOUP',
                ],
            ],
            [
                'name' => 'Handbags collection heading',
                'type' => 'Content',
                'component' => 'ContentHeading',
                'rows' => 1,
                'cols' => 12,
                'data' => [
                    // @todo Need to support multi language later
                    'heading' => 'HANDBAGS - FW22/23 COLLECTION',
                    'sub_heading' => 'The handbag, loved and wanted accessory for each woman. The final touch for all outfits, trendy and convenient, available in all colors and shapes. Fall for our selection of bags for this winter of luxury brands like Philip Karto, Hogan, Tory Burch, Marc Jacobs,...',
                ],
            ],
            [
                'name' => 'Collection of Handbags',
                'type' => 'Collection',
                'component' => 'CollectionFeaturedGallery',
                'rows' => 1,
                'cols' => 12,
                'params' => [
                    'limit' => 5,
                    'sort' => 'created_at',
                    'order' => 'desc',
                    'collection_id' => $this->getFeaturedCollectionId('Handbags'),
                    'gallery' => [
                        'enable' => true,
                        'layout' => 'featured_left',
                    ],
                ],
            ],
        ])->each(function ($widgetData) use ($slot) {
            $position = $slot->widgets()->count()+1;
            $widget = $slot->widgets()->updateOrCreate($widgetData);
            $slot->widgets()->updateExistingPivot($widget->id, [
                'position' => $position,
                'slot_rows' => $widgetData['rows'],
                'slot_cols' => $widgetData['cols'],
            ]);
        });
    }

    protected function getFeaturedCollectionId(string $name): int
    {
        /** @var Collection $collection */
        $collection = Collection::query()->firstOrCreate([
            'attribute_data->name->value->en' => $name
        ], [
            'type' => 'featured',
            'collection_group_id' => $this->collectionGroup->id,
            'attribute_data' => [
                'name' => new TranslatedText([
                    'en' => new Text($name),
                ]),
            ],
        ]);

        $collection->products()->detach();
        //if ($collection->products->isEmpty()) {
        $products = Product::query()->where('status', 'published')->inRandomOrder()->limit(24)->get();
        $collection->products()->sync($products->pluck('id'));
        //}

        return $collection->id;
    }

    protected function advertisementWidgets(): void
    {
        collect([

        ])->each(function ($widget) {
            Widget::query()->create($widget);
        });
    }

    protected function contentWidgets()
    {
        collect([

        ])->each(function ($widget) {
            Widget::query()->create($widget);
        });
    }

    protected function productWidgets()
    {
        collect([

        ])->each(function ($widget) {
            Widget::query()->create($widget);
        });
    }
}
