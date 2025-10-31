<?php

require_once 'SmsInterface.php';

// Third-party Vonage SDK (we can't modify this)
class VonageClient
{
    public function send(array $params): object
    {
        return (object)[
            'message-id' => uniqid(),
            'status' => 0, // 0 = success
            'from' => $params['from'],
            'to' => $params['to'],
            'text' => $params['text'],
        ];
    }
}

// Adapter that makes Vonage compatible with our interface
class VonageAdapter implements SmsInterface
{
    private string $fromNumber;
    
    public function __construct(
        private VonageClient $client,
        string $fromNumber = '1234567890'
    ) {
        $this->fromNumber = $fromNumber;
    }
    
    public function send(string $to, string $message): bool
    {
        echo "📱 Sending via Vonage...\n";
        $result = $this->client->send([
            'from' => $this->fromNumber,
            'to' => $to,
            'text' => $message,
        ]);
        return $result->status === 0;
    }
    
    public function getProvider(): string
    {
        return 'Vonage';
    }
}
