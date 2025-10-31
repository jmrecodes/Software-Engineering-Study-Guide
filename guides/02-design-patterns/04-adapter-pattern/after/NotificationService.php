<?php

require_once 'SmsInterface.php';

class NotificationService
{
    public function __construct(private SmsInterface $smsProvider) {}
    
    public function sendSms(string $to, string $message): bool
    {
        echo "Using {$this->smsProvider->getProvider()} provider\n";
        return $this->smsProvider->send($to, $message);
    }
    
    // Easy to switch providers at runtime
    public function setProvider(SmsInterface $provider): void
    {
        $this->smsProvider = $provider;
    }
}
