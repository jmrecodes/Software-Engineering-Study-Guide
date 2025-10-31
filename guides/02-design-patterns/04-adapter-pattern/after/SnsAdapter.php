<?php

require_once 'SmsInterface.php';

// Third-party AWS SNS SDK (we can't modify this)
class SnsClient
{
    public function publish(array $args): array
    {
        return [
            'MessageId' => uniqid(),
            'ResponseMetadata' => ['HTTPStatusCode' => 200],
            'PhoneNumber' => $args['PhoneNumber'],
            'Message' => $args['Message'],
        ];
    }
}

// Adapter that makes SNS compatible with our interface
class SnsAdapter implements SmsInterface
{
    public function __construct(private SnsClient $client) {}
    
    public function send(string $to, string $message): bool
    {
        echo "📱 Sending via AWS SNS...\n";
        $result = $this->client->publish([
            'PhoneNumber' => $to,
            'Message' => $message,
        ]);
        return $result['ResponseMetadata']['HTTPStatusCode'] === 200;
    }
    
    public function getProvider(): string
    {
        return 'AWS SNS';
    }
}
