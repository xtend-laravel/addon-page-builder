<?php

namespace PageBuilder\Database\Seeders;

use Illuminate\Database\Seeder;
use XtendLunar\Addons\PageBuilder\Models\BlogCategory;

class BlogCategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'        => [
                    'en' => 'Technology',
                    'ar' => 'تكنولوجيا',
                    'fr' => 'Technologie',
                ],
                'description' => [
                    'en' => 'Technology related articles',
                    'ar' => 'مقالات تكنولوجيا ذات صلة',
                    'fr' => 'Articles liés à la technologie',
                ],
                'slug'        => 'technology',
                'is_visible'  => true,
            ],
            [
                'name'        => [
                    'en' => 'Fashion',
                    'ar' => 'موضة',
                    'fr' => 'Mode',
                ],
                'description' => [
                    'en' => 'Fashion related articles',
                    'ar' => 'مقالات ذات صلة بالأزياء',
                    'fr' => 'Articles liés à la mode',
                ],
                'slug'        => 'fashion',
                'is_visible'  => true,
            ],
            [
                'name'        => [
                    'en' => 'Design & Trends',
                    'ar' => 'تصميم واتجاهات',
                    'fr' => 'Conception et tendances',
                ],
                'description' => [
                    'en' => 'Design and trend related articles',
                    'ar' => 'مقالات ذات صلة بالتصميم والاتجاهات',
                    'fr' => 'Articles liés à la conception et aux tendances',
                ],
                'slug'        => 'design-trends',
                'is_visible'  => true,
            ]
        ];

        foreach ($data as $category) {
            BlogCategory::create($category);
        }
    }
}