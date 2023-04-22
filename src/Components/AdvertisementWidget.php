<?php

namespace XtendLunar\Addons\PageBuilder\Components;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use XtendLunar\Addons\PageBuilder\Contracts\Widget;
use XtendLunar\Addons\PageBuilder\Fields\Image;

abstract class AdvertisementWidget implements Widget
{
    public function schema(): array
    {
        return [
            Fieldset::make('Data')
                ->schema([
                    TextInput::make('data.title')->columnSpan(2),
                    Textarea::make('data.description')->columnSpan(2),
                    Section::make('Media')
                        ->schema([
                            Placeholder::make('media_type_label')->label('Select media type')->columnSpan(2),
                            Radio::make('data.media_type')
                                ->disableLabel()
                                ->hint('Select the type of media to display')
                                ->inline()
                                ->reactive()
                                ->options([
                                    'image_url' => 'Image URL',
                                    'image_upload' => 'Image upload',
                                    'video_embed' => 'Video embed',
                                ]),
                            TextInput::make('data.image')
                                ->hidden(fn(\Closure $get) => $get('data.media_type') !== 'image_url')
                                ->columnSpan(2),
                            Image::make('upload_image')->imagePreviewHeight(100)
                                ->hidden(fn(\Closure $get) => $get('data.media_type') !== 'image_upload')
                                ->columnSpan(2),
                            TextInput::make('data.video')
                                ->hidden(fn(\Closure $get) => $get('data.media_type') !== 'video_embed')
                                ->columnSpan(2),
                    ]),
                    TextInput::make('data.cta')->label('Call to action text')->columnSpan(1),
                    TextInput::make('data.route')->label('Url')->columnSpan(1),
                    TextInput::make('data.placement')->hidden(),
                ])->columns(2)
        ];
    }
}
