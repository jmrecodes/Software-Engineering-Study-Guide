<?php

/**
 * Observer Pattern - PHP 8.3 Complete Example
 * 
 * Run: php demo.php
 */

// ============================================================================
// OBSERVER INTERFACE
// ============================================================================

interface Observer
{
    public function update(Subject $subject): void;
    public function getName(): string;
}

// ============================================================================
// SUBJECT INTERFACE
// ============================================================================

interface Subject
{
    public function attach(Observer $observer): void;
    public function detach(Observer $observer): void;
    public function notify(): void;
    public function getData(): array;
}

// ============================================================================
// CONCRETE SUBJECT
// ============================================================================

class Order implements Subject
{
    private array $observers = [];
    private int $id;
    private string $customerEmail;
    private string $customerPhone;
    private array $items = [];
    private float $total = 0;
    
    public function __construct()
    {
        $this->id = rand(1000, 9999);
    }
    
    public function attach(Observer $observer): void
    {
        $this->observers[] = $observer;
        echo "➕ Attached: {$observer->getName()}\n";
    }
    
    public function detach(Observer $observer): void
    {
        $this->observers = array_filter(
            $this->observers,
            fn($obs) => $obs !== $observer
        );
        echo "➖ Detached: {$observer->getName()}\n";
    }
    
    public function notify(): void
    {
        echo "📢 Notifying " . count($this->observers) . " observers...\n\n";
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
    
    public function getData(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->customerEmail,
            'phone' => $this->customerPhone,
            'items' => $this->items,
            'total' => $this->total,
        ];
    }
    
    public function setCustomer(string $email, string $phone): void
    {
        $this->customerEmail = $email;
        $this->customerPhone = $phone;
    }
    
    public function addItem(string $name, float $price): void
    {
        $this->items[] = ['name' => $name, 'price' => $price];
        $this->total += $price;
    }
    
    public function place(): void
    {
        echo "\n🛒 Placing order #{$this->id}...\n";
        $this->notify();
        echo "✅ Order #{$this->id} placed successfully!\n";
    }
}

// ============================================================================
// CONCRETE OBSERVERS
// ============================================================================

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

class SlackNotifier implements Observer
{
    public function update(Subject $subject): void
    {
        $data = $subject->getData();
        echo "💬 Posting order notification to Slack: Order #{$data['id']} - \${$data['total']}\n";
    }
    
    public function getName(): string
    {
        return 'Slack Notifier';
    }
}

// ============================================================================
// USAGE DEMONSTRATION
// ============================================================================

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║          Observer Pattern - Order System Demo                ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Create order
$order = new Order();

// Attach observers
echo "Attaching observers:\n";
$emailNotifier = new EmailNotifier();
$inventoryUpdater = new InventoryUpdater();
$analyticsTracker = new AnalyticsTracker();
$invoiceGenerator = new InvoiceGenerator();
$smsNotifier = new SmsNotifier();
$slackNotifier = new SlackNotifier();

$order->attach($emailNotifier);
$order->attach($inventoryUpdater);
$order->attach($analyticsTracker);
$order->attach($invoiceGenerator);
$order->attach($smsNotifier);
$order->attach($slackNotifier);

// Build order
$order->setCustomer('customer@example.com', '+1234567890');
$order->addItem('Laptop', 999.99);
$order->addItem('Mouse', 29.99);
$order->addItem('Keyboard', 79.99);

// Place order - all observers will be notified
$order->place();

// Demonstrate dynamic detachment
echo "\n" . str_repeat('-', 60) . "\n";
echo "\nRemoving SMS and Slack notifications:\n";
$order->detach($smsNotifier);
$order->detach($slackNotifier);

// Create another order with fewer observers
$order2 = new Order();
$order2->attach($emailNotifier);
$order2->attach($inventoryUpdater);
$order2->attach($analyticsTracker);
$order2->attach($invoiceGenerator);

$order2->setCustomer('another@example.com', '+0987654321');
$order2->addItem('Monitor', 349.99);
$order2->place();

echo "\n╔══════════════════════════════════════════════════════════════╗\n";
echo "║                    ✨ Demo Complete ✨                        ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

echo "Key Benefits Demonstrated:\n";
echo "  ✅ Loose coupling between Order and observers\n";
echo "  ✅ Easy to add/remove observers at runtime\n";
echo "  ✅ Order doesn't know about concrete observer classes\n";
echo "  ✅ Each observer handles one responsibility\n";
echo "  ✅ New observers don't require modifying Order\n";
