<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Event::listen('Illuminate\\Auth\\Events\\Login', function ($event) {
            $request = request();
            $agent = $request->header('User-Agent');
            $ip = $request->ip();
            $parsed = $this->parseUserAgent($agent);
            \App\Models\UserSession::updateOrCreate(
                [ 'session_id' => session()->getId() ],
                [
                    'user_id' => $event->user->id,
                    'ip_address' => $ip,
                    'user_agent' => $agent,
                    'device' => $parsed['device'] ?? null,
                    'platform' => $parsed['platform'] ?? null,
                    'browser' => $parsed['browser'] ?? null,
                    'location' => null,
                    'last_activity' => now(),
                ]
            );
        });

        Event::listen('Illuminate\\Session\\Events\\SessionRegenerated', function () {
            if (auth()->check()) {
                \App\Models\UserSession::where('session_id', session()->getId())
                    ->update(['last_activity' => now()]);
            }
        });
    }

    private function parseUserAgent(?string $ua): array
    {
        $result = ['device' => null, 'platform' => null, 'browser' => null];
        if (!$ua) return $result;
        if (stripos($ua, 'Windows') !== false) $result['platform'] = 'Windows';
        elseif (stripos($ua, 'Mac OS') !== false || stripos($ua, 'Macintosh') !== false) $result['platform'] = 'macOS';
        elseif (stripos($ua, 'Linux') !== false) $result['platform'] = 'Linux';
        if (stripos($ua, 'Chrome') !== false) $result['browser'] = 'Chrome';
        elseif (stripos($ua, 'Firefox') !== false) $result['browser'] = 'Firefox';
        elseif (stripos($ua, 'Safari') !== false) $result['browser'] = 'Safari';
        $result['device'] = (stripos($ua, 'Mobile') !== false) ? 'Mobile' : 'Desktop';
        return $result;
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
