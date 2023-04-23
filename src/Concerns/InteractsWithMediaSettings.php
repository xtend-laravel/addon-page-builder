<?php

namespace XtendLunar\Addons\PageBuilder\Concerns;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;

trait InteractsWithMediaSettings
{
    protected function mediaSchema(): array
    {
        return [
            Section::make(function (\Closure $get, \Closure $set) {
                $set('data.media_type', $get('data.media_type') ?? 'image_upload');
                return $get('data.media_type') === 'image_url' ? 'Image URL' : ($get('data.media_type') === 'image_upload' ? 'Image upload' : 'Video embed');
            })
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
                    FileUpload::make('data.upload_image')
                        ->hidden(fn(\Closure $get) => $get('data.media_type') !== 'image_upload')
                        ->columnSpan(2),
                    TextInput::make('data.video')
                        ->hidden(fn(\Closure $get) => $get('data.media_type') !== 'video_embed')
                        ->columnSpan(2),
            ]),
        ];
    }
}
