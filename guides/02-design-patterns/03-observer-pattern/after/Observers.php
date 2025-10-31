<?php

require_once 'Observer.php';

class EmailNotifier implements Observer
{
    public function update(Subject $subject): void
    {
        $data = $subject->getData();
        echo "📧 Sending order confirmation email to {$data['email']} for order #{$data['id']} (\${$data['total']})\n";
    }
    
    public function getName(): string
    {
        return 'Email Notifier';
    }
}

class InventoryUpdater implements Observer
{
    public function update(Subject $subject): void
    {
        $data = $subject->getData();
        echo "📦 Updating inventory for " . count($data['items']) . " items\n";
    }
    
    public function getName(): string
    {
        return 'Inventory Updater';
    }
}

class AnalyticsTracker implements Observer
{
    public function update(Subject $subject): void
    {
        $data = $subject->getData();
        echo "📊 Tracking purchase in analytics: Order #{$data['id']} - \${$data['total']}\n";
    }
    
    public function getName(): string
    {
        return 'Analytics Tracker';
    }
}

class InvoiceGenerator implements Observer
{
    public function update(Subject $subject): void
    {
        $data = $subject->getData();
        echo "🧾 Generating invoice for order #{$data['id']} - \${$data['total']}\n";
    }
    
    public function getName(): string
    {
        return 'Invoice Generator';
    }
}

class SmsNotifier implements Observer
{
    public function update(Subject $subject): void
    {
        $data = $subject->getData();
        echo "📱 Sending SMS to {$data['phone']} about order #{$data['id']}\n";
    }
    
    public function getName(): string
    {
        return 'SMS Notifier';
    }
}
