<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LatestPosts extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Posts', Post::count()),
            Stat::make('Latest Post', Post::latest()->first()?->title),
            Stat::make('Most Viewed', Post::orderBy('views', 'desc')->first()?->title),
        ];
    }
}
