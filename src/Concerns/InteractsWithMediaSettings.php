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
                $set('data.media_type', $get('data.media_type') ?? 'image_url');
                return 'Media';
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
                        ])
                        ->disableOptionWhen(fn(\Closure $get, $value) => $value === 'image_url' && ($get('data.image_upload') && count($get('data.image_upload')))),
                    TextInput::make('data.image_url')
                        ->hidden(fn(\Closure $get) => $get('data.media_type') !== 'image_url')
                        ->columnSpan(2),
                    FileUpload::make('data.image_upload')
                        ->visibility('private')
                        ->directory('builder/images')
                        ->preserveFilenames()
                        ->hidden(fn(\Closure $get) => $get('data.media_type') !== 'image_upload')
                        ->columnSpan(2),
                    TextInput::make('data.video')
                        ->hidden(fn(\Closure $get) => $get('data.media_type') !== 'video_embed')
                        ->columnSpan(2),
            ]),
        ];
    }
}
