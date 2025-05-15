<?php

namespace App\Filament\Pages\CustomizeTemplate;

use Filament\Pages\Page;
use http\Encoding\Stream\Deflate;

class InvitationType extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.customize-template.invitation-type';

    protected static bool $shouldRegisterNavigation = false;
}
