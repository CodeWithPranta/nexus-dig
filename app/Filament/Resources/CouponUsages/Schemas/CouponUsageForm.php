<?php

namespace App\Filament\Resources\CouponUsages\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class CouponUsageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('coupon_id')
                    ->relationship('coupon', 'id')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('order_id')
                    ->relationship('order', 'id')
                    ->required(),
            ]);
    }
}
