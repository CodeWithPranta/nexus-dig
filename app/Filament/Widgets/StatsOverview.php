<?php

namespace App\Filament\Widgets;

use App\Enums\Role;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\ServiceInquiry;
use App\Models\User;
use App\Support\Currency;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $revenue = Order::query()
            ->where('status', 'paid')
            ->get(['total', 'currency'])
            ->sum(fn (Order $order): float => Currency::convert((float) $order->total, $order->currency ?: 'BDT', 'BDT'));
        $paidOrders = Order::query()->where('status', 'paid')->count();
        $students = User::query()->where('role', Role::Student->value)->count();
        $activeEnrollments = Enrollment::query()->where('status', 'active')->count();
        $pendingCourses = Course::query()->where('status', Course::STATUS_PENDING)->count();
        $newInquiries = ServiceInquiry::query()->where('status', 'new')->count();

        return [
            Stat::make('Revenue', Currency::format($revenue, 'BDT'))
                ->description('Paid orders')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make('Paid orders', (string) $paidOrders)
                ->description('Completed payments')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info'),
            Stat::make('Students', (string) $students)
                ->description('Registered learners')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
            Stat::make('Active enrollments', (string) $activeEnrollments)
                ->description('Currently learning')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),
            Stat::make('Pending courses', (string) $pendingCourses)
                ->description('Awaiting review')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('New inquiries', (string) $newInquiries)
                ->description('Service leads')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color('danger'),
        ];
    }
}
