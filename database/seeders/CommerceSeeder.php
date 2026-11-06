<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommerceSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::where('email', 'student@nexusdig.com')->first();

        if (! $student) {
            return;
        }

        $courses = Course::query()
            ->where('status', Course::STATUS_PUBLISHED)
            ->orderBy('id')
            ->take(2)
            ->get();

        foreach ($courses as $index => $course) {
            $order = Order::updateOrCreate(
                ['order_number' => 'ND-'.str_pad((string) ($index + 1), 5, '0', STR_PAD_LEFT)],
                [
                    'user_id' => $student->id,
                    'subtotal' => $course->price,
                    'discount_total' => 0,
                    'total' => $course->effective_price,
                    'currency' => $course->currency,
                    'status' => 'paid',
                    'paid_at' => now()->subDays(10 - $index),
                ],
            );

            OrderItem::updateOrCreate(
                ['order_id' => $order->id, 'course_id' => $course->id],
                [
                    'course_title' => $course->title,
                    'price' => $course->price,
                    'discount' => 0,
                    'total' => $course->effective_price,
                ],
            );

            Payment::updateOrCreate(
                ['order_id' => $order->id, 'transaction_ref' => 'TXN-'.$order->id],
                [
                    'provider' => 'stripe',
                    'amount' => $order->total,
                    'currency' => $order->currency,
                    'status' => 'succeeded',
                    'paid_at' => $order->paid_at,
                ],
            );

            Enrollment::updateOrCreate(
                ['user_id' => $student->id, 'course_id' => $course->id],
                [
                    'order_id' => $order->id,
                    'status' => 'active',
                    'lifetime_access' => true,
                    'enrolled_at' => $order->paid_at,
                ],
            );

            $course->update([
                'enrollment_count' => $course->enrollments()->count(),
            ]);
        }
    }
}
