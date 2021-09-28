<?php

namespace App\Repositories;

use App\Models\Subscription;
use Illuminate\Support\Facades\Cache;

class SubscriptionRepository
{
    public array $recipt_data;
    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    private function getCurrentSubcription(): Subscription
    {
        return Subscription::firstOrNew(['token'=>$this->token]);
    }

    public function updateOrCreateSubscription(array $recipt_data)
    {
        $current_subscription = $this->getCurrentSubcription();
        $this->recipt_data = $recipt_data;

        return $this->recipt_data['status'] ? $this->processValidRecipt($current_subscription)
        : $this->processInValidRecipt($current_subscription);
    }

    private function processValidRecipt(Subscription $subscription): Subscription
    {
        return $subscription->status ? $this->renewSubscription($subscription) :
        $this->createSubscription($subscription);
    }

    private function processInValidRecipt(Subscription $subscription): ?Subscription
    {
        return ($subscription->status && $subscription->status != 'canceled') ? $this->cancelSubscription($subscription)
                : null;
    }

    private function createSubscription(Subscription $subscription): Subscription
    {
        $subscription->fill([
            'status' => 'started',
            'subscribed_at' => now(),
            'valid_until' => $this->recipt_data['expire_at'],
            'canceld_at' => null
        ]);

        $subscription->save();
        $this->setSubscriptionCahce($subscription);

        return $subscription;
    }

    private function renewSubscription(Subscription $subscription): Subscription
    {
        $subscription->update([
            'status' => 'renewed',
            'subscribed_at' => now(),
            'valid_until' => $this->recipt_data['expire_at'],
            'canceld_at' => null
           ]);

        $changes = $subscription->refresh();
        $this->setSubscriptionCahce($changes);

        return $changes;
    }

    private function cancelSubscription(Subscription $subscription): Subscription
    {
        $subscription->update(
            [
                'status'=>'canceled',
                'canceled_at'=>now(),
                'valid_until'=>null,
            ]
        );

        $changes = $subscription->refresh();
        $this->setSubscriptionCahce($changes);

        return $changes;
    }

    private function getAppId(): string
    {
        return Cache::get($this->token)->device->app_id;
    }

    private function setSubscriptionCahce(?Subscription $subscription): void
    {
        $subscription->setSubscriptionCahce($this->token);
    }
}
