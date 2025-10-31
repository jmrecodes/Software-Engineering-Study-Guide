<?php

require_once 'Subject.php';
require_once 'Observer.php';

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
    }
    
    public function detach(Observer $observer): void
    {
        $this->observers = array_filter(
            $this->observers,
            fn($obs) => $obs !== $observer
        );
    }
    
    public function notify(): void
    {
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
        echo "\n🛒 Placing order #{$this->id}...\n\n";
        $this->notify();
        echo "\n✅ Order #{$this->id} placed successfully!\n";
    }
}
