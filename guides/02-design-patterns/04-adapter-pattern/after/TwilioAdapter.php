<?php

require_once 'SmsInterface.php';

// Third-party Twilio SDK (we can't modify this)
class TwilioClient
{
    public function sendSms(string $from, string $to, string $body): array
    {
        return [
            'sid' => 'SM' . uniqid(),
            'status' => 'sent',
            'from_number' => $from,
            'to_number' => $to,
            'message' => $body,
        ];
    }
}

// Adapter that makes Twilio compatible with our interface
class TwilioAdapter implements SmsInterface
{
    private string $fromNumber;
    
    public function __construct(
        private TwilioClient $client,
        string $fromNumber = '+1234567890'
    ) {
        $this->fromNumber = $fromNumber;
    }
    
    public function send(string $to, string $message): bool
    {
        echo "📱 Sending via Twilio...\n";
        $result = $this->client->sendSms($this->fromNumber, $to, $message);
        return $result['status'] === 'sent';
    }
    
    public function getProvider(): string
    {
        return 'Twilio';
    }
}
