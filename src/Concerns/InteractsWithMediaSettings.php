<?php

namespace XtendLunar\Addons\PageBuilder\Concerns;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use XtendLunar\Addons\PageBuilder\Fields\Image;

trait InteractsWithMediaSettings
{
    protected function mediaSchema(): array
    {
        return [
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
        ];
    }
}
