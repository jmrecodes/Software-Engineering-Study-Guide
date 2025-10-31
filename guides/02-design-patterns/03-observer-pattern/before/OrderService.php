<?php

/**
 * BEFORE: The Problem
 * 
 * This code demonstrates what happens WITHOUT the Observer Pattern.
 * Notice the tight coupling and how the Order class knows about all dependencies.
 */

class EmailService
{
    public function sendOrderConfirmation(string $email, int $orderId, float $total): void
    {
        echo "📧 Sending order confirmation email to {$email} for order #{$orderId} (\${$total})\n";
    }
}

class InventoryService
{
    public function updateStock(array $items): void
    {
        echo "📦 Updating inventory for " . count($items) . " items\n";
    }
}

class AnalyticsService
{
    public function trackPurchase(int $orderId, float $total): void
    {
        echo "📊 Tracking purchase in analytics: Order #{$orderId} - \${$total}\n";
    }
}

class InvoiceService
{
    public function generateInvoice(int $orderId, float $total): void
    {
        echo "🧾 Generating invoice for order #{$orderId} - \${$total}\n";
    }
}

class SmsService
{
    public function sendOrderNotification(string $phone, int $orderId): void
    {
        echo "📱 Sending SMS to {$phone} about order #{$orderId}\n";
    }
}

// PROBLEM: Order is tightly coupled to all these services
class Order
{
    private int $id;
    private string $customerEmail;
    private string $customerPhone;
    private array $items;
    private float $total;
    
    // All dependencies must be injected
    public function __construct(
        private EmailService $emailService,
        private InventoryService $inventoryService,
        private AnalyticsService $analyticsService,
        private InvoiceService $invoiceService,
        private SmsService $smsService
    ) {
        $this->id = rand(1000, 9999);
        $this->items = [];
        $this->total = 0;
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
        echo "\n🛒 Placing order #{$this->id}...\n\n";
        
        // PROBLEM: Order class has to manually notify every service
        // If we add a new service, we must modify this class
        $this->emailService->sendOrderConfirmation($this->customerEmail, $this->id, $this->total);
        $this->inventoryService->updateStock($this->items);
        $this->analyticsService->trackPurchase($this->id, $this->total);
        $this->invoiceService->generateInvoice($this->id, $this->total);
        $this->smsService->sendOrderNotification($this->customerPhone, $this->id);
        
        echo "\n✅ Order #{$this->id} placed successfully!\n";
    }
}

// Usage - Notice all dependencies must be created
$emailService = new EmailService();
$inventoryService = new InventoryService();
$analyticsService = new AnalyticsService();
$invoiceService = new InvoiceService();
$smsService = new SmsService();

$order = new Order(
    $emailService,
    $inventoryService,
    $analyticsService,
    $invoiceService,
    $smsService
);

echo "=== PROBLEMATIC APPROACH (Before Observer Pattern) ===\n";

$order->setCustomer('customer@example.com', '+1234567890');
$order->addItem('Laptop', 999.99);
$order->addItem('Mouse', 29.99);
$order->addItem('Keyboard', 79.99);
$order->place();

/**
 * PROBLEMS WITH THIS APPROACH:
 * 
 * 1. Tight Coupling
 *    - Order class directly depends on 5 different services
 *    - Can't test Order in isolation
 * 
 * 2. Violation of Open/Closed Principle
 *    - Adding a new notification (e.g., Slack) requires modifying Order class
 *    - Can't extend without modification
 * 
 * 3. Violation of Single Responsibility Principle
 *    - Order class responsible for order logic AND notifications
 *    - Too many responsibilities
 * 
 * 4. Hard to Maintain
 *    - If a service changes, Order might need to change
 *    - Order must know about implementation details
 * 
 * 5. No Flexibility
 *    - Can't disable certain notifications easily
 *    - Can't add notifications at runtime
 * 
 * 6. Difficult Testing
 *    - Must mock all 5 services to test Order
 *    - Complex test setup
 * 
 * 7. Order of Execution
 *    - Order of notifications is hardcoded
 *    - Can't change priority without modifying code
 */
