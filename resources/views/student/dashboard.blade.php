@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold">{{ __('app.dashboard_welcome', ['name' => $user->name]) }}</h1>
                <p class="mut mt-1">{{ __('app.dashboard_subtitle') }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <div class="text-right">
                    <p class="mut text-sm">{{ __('app.signed_in_as', ['email' => $user->email]) }}</p>
                    <p class="mut text-xs">{{ __('app.member_since', ['date' => $user->created_at?->format('M Y')]) }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-o !py-1.5">{{ __('app.logout') }}</button>
                </form>
            </div>
        </div>

        <div class="grid sm:grid-cols-3 gap-4 mt-8">
            <div class="card p-5">
                <p class="mut text-sm">{{ __('app.my_courses') }}</p>
                <p class="text-3xl font-bold mt-1">{{ $stats['courses'] }}</p>
            </div>
            <div class="card p-5">
                <p class="mut text-sm">{{ __('app.completed_lessons') }}</p>
                <p class="text-3xl font-bold mt-1">{{ $stats['completed'] }}</p>
            </div>
            <div class="card p-5">
                <p class="mut text-sm">{{ __('app.upcoming_classes') }}</p>
                <p class="text-3xl font-bold mt-1">{{ $stats['upcoming'] }}</p>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6 mt-8">
            <div class="lg:col-span-2 space-y-6">
                <section>
                    <h2 class="text-xl font-semibold">{{ __('app.continue_learning') }}</h2>

                    @forelse ($enrollments as $enrollment)
                        @php
                            $total = (int) ($lessonTotals[$enrollment->course_id] ?? 0);
                            $done = (int) ($completedTotals[$enrollment->course_id] ?? 0);
                            $percent = $total > 0 ? min(100, (int) round($done / $total * 100)) : 0;
                            $next = $nextLessons->get($enrollment->course_id);
                        @endphp

                        <div class="card p-5 mt-4">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <h3 class="font-semibold">{{ $enrollment->course->title }}</h3>
                                    <p class="mut text-sm mt-1">
                                        {{ __('app.next') }}
                                        {{ $next?->title ?? __('app.course_completed') }}
                                    </p>
                                </div>
                                <a class="btn !py-1.5" href="{{ route('home') }}#course/{{ $enrollment->course_id }}">
                                    {{ __('app.resume') }}
                                </a>
                            </div>
                            <div class="bar mt-4" role="progressbar" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">
                                <i style="width: {{ $percent }}%"></i>
                            </div>
                            <p class="mut text-xs mt-1">
                                {{ __('app.complete', ['percent' => $percent]) }} · {{ $done }}/{{ $total }} {{ __('app.lessons') }}
                            </p>
                        </div>
                    @empty
                        <div class="card p-6 mt-4 text-center">
                            <p class="mut">{{ __('app.no_enrollments') }}</p>
                            <a href="{{ route('home') }}#courses" class="btn mt-4">{{ __('app.browse_courses') }}</a>
                        </div>
                    @endforelse
                </section>

                <section>
                    <h2 class="text-xl font-semibold">{{ __('app.recent_orders') }}</h2>

                    @if ($orders->isEmpty())
                        <p class="mut text-sm mt-3">{{ __('app.no_orders') }}</p>
                    @else
                        <div class="card overflow-x-auto mt-3">
                            <table class="w-full text-sm">
                                <thead class="mut text-left">
                                    <tr>
                                        <th class="p-3">{{ __('app.order') }}</th>
                                        <th>{{ __('app.amount') }}</th>
                                        <th>{{ __('app.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr class="border-t bd">
                                            <td class="p-3">#{{ $order->order_number }}</td>
                                            <td>{{ \App\Support\Currency::format((float) $order->total, $order->currency ?: 'BDT') }}</td>
                                            <td>
                                                <span class="soft px-2 py-0.5 rounded-full text-xs">{{ __('app.'.$order->status) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>
            </div>

            <aside class="space-y-4">
                <div class="card p-5">
                    <h2 class="font-semibold">{{ __('app.upcoming_classes') }}</h2>

                    @forelse ($upcomingSessions as $session)
                        <div class="mt-3 text-sm">
                            <p class="font-medium">{{ $session->title }}</p>
                            <p class="mut">{{ $session->course?->title }}</p>
                            <p class="mut">{{ $session->starts_at?->format('d M Y, g:i A') }}</p>
                            @if ($session->meeting_url)
                                <a class="btn w-full mt-2 !py-1.5" href="{{ $session->meeting_url }}" target="_blank" rel="noopener">
                                    {{ __('app.join_live') }}
                                </a>
                            @endif
                        </div>
                    @empty
                        <p class="mut text-sm mt-2">{{ __('app.no_upcoming_classes') }}</p>
                    @endforelse
                </div>

                <div class="card p-5">
                    <h2 class="font-semibold">{{ __('app.recordings') }}</h2>

                    @forelse ($recordings as $recording)
                        <a class="flex items-start gap-2 mt-3 text-sm hover:text-[var(--br)]"
                           href="{{ $recording->video_url ?: '#' }}"
                           @if ($recording->video_url) target="_blank" rel="noopener" @endif>
                            <span aria-hidden="true">▶</span>
                            <span>{{ $recording->title }}</span>
                        </a>
                    @empty
                        <p class="mut text-sm mt-2">{{ __('app.no_recordings') }}</p>
                    @endforelse
                </div>
            </aside>
        </div>
    </div>
@endsection
