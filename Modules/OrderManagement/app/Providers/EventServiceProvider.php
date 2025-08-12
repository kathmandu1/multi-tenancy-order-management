<?php

namespace Modules\OrderManagement\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\OrderManagement\Events\OrderCreatedEvent;
use Modules\OrderManagement\Listeners\OrderCreatedListener;
use Modules\OrderManagement\Models\Order;
use Modules\OrderManagement\Models\OrderProduct;
use Modules\OrderManagement\Observers\OrderItemObserver;
use Modules\OrderManagement\Observers\OrderObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        OrderCreatedEvent::class => [
            OrderCreatedListener::class,
        ],
    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void {}

    public function boot(): void
    {
        Order::observe(OrderObserver::class);
        OrderProduct::observe(OrderItemObserver::class);
    }
}
