<?php

/**
 * Adapter Pattern - PHP 8.3 Complete Example
 * 
 * Run: php demo.php
 */

// ============================================================================
// TARGET INTERFACE (What our application expects)
// ============================================================================

interface SmsInterface
{
    public function send(string $to, string $message): bool;
    public function getProvider(): string;
}

// ============================================================================
// ADAPTEES (Third-party libraries we can't modify)
// ============================================================================

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

class VonageClient
{
    public function send(array $params): object
    {
        return (object)[
            'message-id' => uniqid(),
            'status' => 0,
            'from' => $params['from'],
            'to' => $params['to'],
            'text' => $params['text'],
        ];
    }
}

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

// ============================================================================
// ADAPTERS (Make incompatible interfaces compatible)
// ============================================================================

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
        $result = $this->client->sendSms($this->fromNumber, $to, $message);
        echo "  ✓ Twilio SMS sent - SID: {$result['sid']}\n";
        return $result['status'] === 'sent';
    }
    
    public function getProvider(): string
    {
        return 'Twilio';
    }
}

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
        $result = $this->client->send([
            'from' => $this->fromNumber,
            'to' => $to,
            'text' => $message,
        ]);
        echo "  ✓ Vonage SMS sent - ID: {$result->{'message-id'}}\n";
        return $result->status === 0;
    }
    
    public function getProvider(): string
    {
        return 'Vonage';
    }
}

class SnsAdapter implements SmsInterface
{
    public function __construct(private SnsClient $client) {}
    
    public function send(string $to, string $message): bool
    {
        $result = $this->client->publish([
            'PhoneNumber' => $to,
            'Message' => $message,
        ]);
        echo "  ✓ AWS SNS SMS sent - Message ID: {$result['MessageId']}\n";
        return $result['ResponseMetadata']['HTTPStatusCode'] === 200;
    }
    
    public function getProvider(): string
    {
        return 'AWS SNS';
    }
}

// ============================================================================
// CLIENT CODE (Works with any adapter)
// ============================================================================

class NotificationService
{
    public function __construct(private SmsInterface $smsProvider) {}
    
    public function sendSms(string $to, string $message): bool
    {
        echo "📱 Using {$this->smsProvider->getProvider()} provider\n";
        return $this->smsProvider->send($to, $message);
    }
    
    public function setProvider(SmsInterface $provider): void
    {
        $this->smsProvider = $provider;
    }
}

// ============================================================================
// USAGE DEMONSTRATION
// ============================================================================

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║         Adapter Pattern - SMS Notification Demo              ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

$recipient = '+1987654321';
$message = 'Your verification code is: 123456';

// Using Twilio
echo "1️⃣  Sending with Twilio Adapter:\n";
$twilioAdapter = new TwilioAdapter(new TwilioClient());
$service = new NotificationService($twilioAdapter);
$service->sendSms($recipient, $message);

echo "\n" . str_repeat('-', 60) . "\n\n";

// Using Vonage
echo "2️⃣  Sending with Vonage Adapter:\n";
$vonageAdapter = new VonageAdapter(new VonageClient());
$service->setProvider($vonageAdapter);
$service->sendSms($recipient, $message);

echo "\n" . str_repeat('-', 60) . "\n\n";

// Using AWS SNS
echo "3️⃣  Sending with AWS SNS Adapter:\n";
$snsAdapter = new SnsAdapter(new SnsClient());
$service->setProvider($snsAdapter);
$service->sendSms($recipient, $message);

echo "\n" . str_repeat('-', 60) . "\n\n";

// Dynamic provider selection
echo "4️⃣  Dynamic Provider Selection:\n\n";

function createSmsProvider(string $provider): SmsInterface
{
    return match(strtolower($provider)) {
        'twilio' => new TwilioAdapter(new TwilioClient()),
        'vonage' => new VonageAdapter(new VonageClient()),
        'sns', 'aws' => new SnsAdapter(new SnsClient()),
        default => throw new Exception("Unknown provider: {$provider}")
    };
}

$config = 'twilio'; // Could come from .env or database
$provider = createSmsProvider($config);
$dynamicService = new NotificationService($provider);
$dynamicService->sendSms($recipient, 'Dynamic provider test');

echo "\n╔══════════════════════════════════════════════════════════════╗\n";
echo "║                    ✨ Demo Complete ✨                        ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

echo "Key Benefits Demonstrated:\n";
echo "  ✅ Incompatible APIs made compatible through adapters\n";
echo "  ✅ NotificationService doesn't know about specific providers\n";
echo "  ✅ Easy to switch providers without changing client code\n";
echo "  ✅ New providers can be added without modifying existing code\n";
echo "  ✅ Each adapter handles one integration\n";
