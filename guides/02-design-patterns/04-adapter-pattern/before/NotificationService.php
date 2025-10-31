<?php

/**
 * BEFORE: The Problem
 * 
 * This code demonstrates what happens WITHOUT the Adapter Pattern.
 * Notice how the code is tightly coupled to specific third-party libraries.
 */

// Simulated third-party Twilio SDK
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

// Simulated third-party Vonage SDK
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

// Simulated AWS SNS SDK
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

// PROBLEM: NotificationService is tightly coupled to all providers
class NotificationService
{
    public function __construct(
        private string $provider,
        private TwilioClient|VonageClient|SnsClient $client
    ) {}
    
    public function sendSms(string $to, string $message): bool
    {
        // PROBLEM: Different conditional logic for each provider
        if ($this->provider === 'twilio' && $this->client instanceof TwilioClient) {
            echo "Sending via Twilio...\n";
            $result = $this->client->sendSms('+1234567890', $to, $message);
            echo "Twilio Result: " . json_encode($result) . "\n";
            return $result['status'] === 'sent';
            
        } elseif ($this->provider === 'vonage' && $this->client instanceof VonageClient) {
            echo "Sending via Vonage...\n";
            $result = $this->client->send([
                'from' => '1234567890',
                'to' => $to,
                'text' => $message,
            ]);
            echo "Vonage Result: " . json_encode($result) . "\n";
            return $result->status === 0;
            
        } elseif ($this->provider === 'sns' && $this->client instanceof SnsClient) {
            echo "Sending via AWS SNS...\n";
            $result = $this->client->publish([
                'PhoneNumber' => $to,
                'Message' => $message,
            ]);
            echo "SNS Result: " . json_encode($result) . "\n";
            return $result['ResponseMetadata']['HTTPStatusCode'] === 200;
        }
        
        return false;
    }
}

// Usage - Notice the complexity
echo "=== PROBLEMATIC APPROACH (Before Adapter Pattern) ===\n\n";

$twilioService = new NotificationService('twilio', new TwilioClient());
$twilioService->sendSms('+1987654321', 'Hello from Twilio!');

echo "\n";

$vonageService = new NotificationService('vonage', new VonageClient());
$vonageService->sendSms('+1987654321', 'Hello from Vonage!');

echo "\n";

$snsService = new NotificationService('sns', new SnsClient());
$snsService->sendSms('+1987654321', 'Hello from AWS SNS!');

/**
 * PROBLEMS WITH THIS APPROACH:
 * 
 * 1. Tight Coupling
 *    - NotificationService directly depends on three different SDK classes
 *    - Knows implementation details of each provider
 * 
 * 2. Violation of Open/Closed Principle
 *    - Adding a new SMS provider requires modifying NotificationService
 *    - Can't extend without modification
 * 
 * 3. Violation of Single Responsibility Principle
 *    - NotificationService handles business logic AND provider-specific integration
 *    - Too many responsibilities
 * 
 * 4. Complex Conditionals
 *    - if/else chains for different providers
 *    - Hard to maintain and test
 * 
 * 5. No Common Interface
 *    - Each provider has completely different methods and signatures
 *    - Can't treat providers uniformly
 * 
 * 6. Hard to Test
 *    - Must mock specific SDK classes
 *    - Complex test setup for each provider
 * 
 * 7. Code Duplication
 *    - Provider selection logic repeated wherever SMS is sent
 *    - No reusability
 * 
 * 8. Difficult to Switch Providers
 *    - Changing providers requires code changes throughout application
 *    - Risk of breaking existing functionality
 */
