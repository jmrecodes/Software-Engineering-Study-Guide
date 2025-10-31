# The Software Engineer's Guide to Practical Principles (in PHP & Laravel)

---

## Part 1: The Foundational Mindset

### 1. DRY (Don't Repeat Yourself)

#### Real-World Analogy
Imagine you're a chef who writes down the same recipe on multiple sticky notes scattered around your kitchen. When you improve the recipe, you have to update every single sticky note. Instead, keep one master recipe card in a recipe book—update it once, and everyone uses the improved version.

#### The Problem It Solves & Core Concept
**Problem:** Code duplication leads to maintenance nightmares. When you need to fix a bug or add a feature, you must find and update every duplicate instance. Miss one, and you introduce inconsistencies and bugs.

**Core Concept:** Every piece of knowledge or logic should have a single, unambiguous representation in your system. If you find yourself copy-pasting code, you're probably violating DRY.

#### Before (The Problem) - Duplicated Code
```php
<?php

class UserController
{
    public function createUser(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        return redirect()->to('software-engineer-guide/README.md');
{
    public function create(array $data): Content;
    public function read(int $id): Content;
    public function update(int $id, array $data): Content;
    public function delete(int $id): bool;
    public function publish(int $id): bool;
    public function archive(int $id): bool;
    public function restore(int $id): bool;
    public function export(int $id): string;
    public function import(string $data): Content;
}

// A guest user forced to implement everything, then throw exceptions!
class GuestContentManager implements ContentManager
{
    public function create(array $data): Content { throw new \Exception("Unauthorized"); }
    public function read(int $id): Content { /* only this is allowed */ }
    public function update(int $id, array $data): Content { throw new \Exception("Unauthorized"); }
    public function delete(int $id): bool { throw new \Exception("Unauthorized"); }
    // ... etc
}

// AFTER: Segregated interfaces ✅
interface Readable
{
    public function read(int $id): Content;
}

interface Writable
{
    public function create(array $data): Content;
    public function update(int $id, array $data): Content;
    public function delete(int $id): bool;
}

interface Publishable
{
    public function publish(int $id): bool;
    public function archive(int $id): bool;
}

interface Exportable
{
    public function export(int $id): string;
    public function import(string $data): Content;
}

// Guest only implements what they can do
class GuestContentManager implements Readable
{
    public function read(int $id): Content
    {
        return Content::where('published', true)->findOrFail($id);
    }
}

// Editor implements read and write
class EditorContentManager implements Readable, Writable
{
    public function read(int $id): Content { /* ... */ }
    public function create(array $data): Content { /* ... */ }
    public function update(int $id, array $data): Content { /* ... */ }
    public function delete(int $id): bool { /* ... */ }
}

// Admin implements everything
class AdminContentManager implements Readable, Writable, Publishable, Exportable
{
    // Implements all methods
}
```

#### Laravel-Specific Example
**Laravel's Eloquent relationships** demonstrate ISP well. Instead of one giant interface, there are specific interfaces for each relationship type:

```php
// Instead of one fat interface with all relationship methods,
// Laravel provides specific relationship classes:

class User extends Model
{
    // Each relationship is a specific type
    public function posts(): HasMany { /* ... */ }
    public function profile(): HasOne { /* ... */ }
    public function roles(): BelongsToMany { /* ... */ }
}

// You only use the relationship methods you need
// No forced implementation of irrelevant methods
```

**Common ISP violation in Laravel:**
```php
// Bad: Fat repository interface ❌
interface RepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function restore($id);
    public function forceDelete($id);
    public function search(string $query);
    public function filter(array $filters);
    public function export();
    public function import(string $file);
}

// Read-only models forced to implement write methods!

// Good: Segregated interfaces ✅
interface ReadableRepository
{
    public function all();
    public function find($id);
}

interface WritableRepository
{
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}

interface SearchableRepository
{
    public function search(string $query);
    public function filter(array $filters);
}
```

---

### D - Dependency Inversion Principle (DIP)

#### Real-World Analogy
You don't buy a lamp hardwired to your house. You buy a lamp with a standard plug (abstraction) that fits any outlet (implementation). If you change outlets or move to another country with different outlets, you use an adapter—you don't rewire the lamp.

#### The Problem It Solves & Core Concept
**Problem:** High-level modules directly depend on low-level modules. This creates tight coupling. Changes in low-level details (database, email service, payment gateway) ripple up and break high-level business logic.

**Core Concept:** 
1. High-level modules should not depend on low-level modules. Both should depend on abstractions (interfaces).
2. Abstractions should not depend on details. Details should depend on abstractions.

In practice: **Depend on interfaces, not concrete classes**. Inject dependencies instead of creating them internally.

#### Before (Violation) - Direct Dependency
```php
<?php

namespace App\Services;

use App\Repositories\MySQLUserRepository;
use App\Mail\SmtpMailer;
use App\Logger\FileLogger;

class UserService
{
    private MySQLUserRepository $userRepository;
    private SmtpMailer $mailer;
    private FileLogger $logger;
    
    public function __construct()
    {
        // High-level UserService directly depends on low-level implementations
        // Tightly coupled to specific implementations
        $this->userRepository = new MySQLUserRepository();
        $this->mailer = new SmtpMailer();
        $this->logger = new FileLogger('/var/log/app.log');
    }
    
    public function register(array $userData): void
    {
        // Business logic tightly coupled to MySQL, SMTP, and File storage
        $user = $this->userRepository->create($userData);
        $this->mailer->send($user->email, 'Welcome!', 'Welcome to our app!');
        $this->logger->log("User {$user->id} registered");
    }
}

// Problems:
// 1. Can't swap MySQL for PostgreSQL without changing UserService
// 2. Can't swap SMTP for SendGrid without changing UserService
// 3. Can't test UserService without hitting real database, email, and filesystem
// 4. UserService knows too much about low-level details
```

#### After (Solution) - Dependency Inversion
```php
<?php

namespace App\Contracts;

// Abstractions (interfaces) - high-level concepts
interface UserRepository
{
    public function create(array $data): User;
    public function find(int $id): ?User;
}

interface Mailer
{
    public function send(string $to, string $subject, string $body): void;
}

interface Logger
{
    public function log(string $message): void;
}

// High-level module depends on abstractions
namespace App\Services;

use App\Contracts\UserRepository;
use App\Contracts\Mailer;
use App\Contracts\Logger;
use App\Models\User;

class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private Mailer $mailer,
        private Logger $logger
    ) {
        // Dependencies injected from outside
        // UserService doesn't know or care about implementations
    }
    
    public function register(array $userData): void
    {
        $user = $this->userRepository->create($userData);
        $this->mailer->send($user->email, 'Welcome!', 'Welcome to our app!');
        $this->logger->log("User {$user->id} registered");
    }
}

// Low-level implementations depend on abstractions
namespace App\Repositories;

use App\Contracts\UserRepository as UserRepositoryInterface;
use App\Models\User;

class MySQLUserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create($data);
    }
    
    public function find(int $id): ?User
    {
        return User::find($id);
    }
}

class PostgreSQLUserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        // Different implementation, same interface
        return User::create($data);
    }
    
    public function find(int $id): ?User
    {
        return User::find($id);
    }
}

namespace App\Mail;

use App\Contracts\Mailer as MailerInterface;

class SmtpMailer implements MailerInterface
{
    public function send(string $to, string $subject, string $body): void
    {
        // SMTP implementation
    }
}

class SendGridMailer implements MailerInterface
{
    public function send(string $to, string $subject, string $body): void
    {
        // SendGrid implementation
    }
}

namespace App\Logging;

use App\Contracts\Logger as LoggerInterface;

class FileLogger implements LoggerInterface
{
    public function __construct(
        private string $logPath
    ) {}
    
    public function log(string $message): void
    {
        file_put_contents($this->logPath, $message . PHP_EOL, FILE_APPEND);
    }
}

class DatabaseLogger implements LoggerInterface
{
    public function log(string $message): void
    {
        \DB::table('logs')->insert(['message' => $message]);
    }
}

// Bind implementations to interfaces in Service Provider
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\UserRepository;
use App\Contracts\Mailer;
use App\Contracts\Logger;
use App\Repositories\MySQLUserRepository;
use App\Mail\SmtpMailer;
use App\Logging\FileLogger;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind interfaces to concrete implementations
        // Change implementations here without touching UserService!
        $this->app->bind(UserRepository::class, MySQLUserRepository::class);
        $this->app->bind(Mailer::class, SmtpMailer::class);
        $this->app->bind(Logger::class, function() {
            return new FileLogger(storage_path('logs/app.log'));
        });
    }
}

// Usage - Laravel automatically resolves dependencies
$userService = app(UserService::class);
$userService->register(['name' => 'John', 'email' => 'john@example.com']);

// Benefits:
// 1. Swap implementations by changing Service Provider only
// 2. Easy to test with mock implementations
// 3. UserService doesn't depend on low-level details
// 4. Follow "code to interfaces, not implementations"
```

#### Testing Example - Why DIP Matters
```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\UserService;
use App\Contracts\UserRepository;
use App\Contracts\Mailer;
use App\Contracts\Logger;
use App\Models\User;

class UserServiceTest extends TestCase
{
    public function test_user_registration(): void
    {
        // Mock dependencies - easy because we depend on interfaces!
        $mockRepository = $this->createMock(UserRepository::class);
        $mockMailer = $this->createMock(Mailer::class);
        $mockLogger = $this->createMock(Logger::class);
        
        $mockUser = new User(['id' => 1, 'email' => 'test@example.com']);
        $mockRepository->expects($this->once())
            ->method('create')
            ->willReturn($mockUser);
            
        $mockMailer->expects($this->once())
            ->method('send')
            ->with('test@example.com', 'Welcome!', 'Welcome to our app!');
            
        $mockLogger->expects($this->once())
            ->method('log')
            ->with('User 1 registered');
        
        // Inject mocks
        $userService = new UserService($mockRepository, $mockMailer, $mockLogger);
        
        // Test
        $userService->register(['name' => 'Test', 'email' => 'test@example.com']);
        
        // No database, email, or file I/O needed for testing!
    }
}
```

#### Laravel-Specific Example
**Laravel's Service Container IS Dependency Inversion in action.**

The entire framework is built on DIP:

```php
// Laravel Controller - DIP in action
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use App\Contracts\OrderRepository;

class OrderController extends Controller
{
    // Depend on abstractions, not concrete classes
    public function __construct(
        private PaymentGateway $paymentGateway,
        private OrderRepository $orderRepository
    ) {}
    
    public function store(Request $request)
    {
        // Controller doesn't know if it's Stripe or PayPal
        // Controller doesn't know if data is in MySQL or MongoDB
        $this->paymentGateway->charge($request->amount);
        $this->orderRepository->create($request->all());
    }
}

// Change implementations without touching controller
// config/app.php or AppServiceProvider
$this->app->bind(PaymentGateway::class, StripeGateway::class);
$this->app->bind(OrderRepository::class, MongoOrderRepository::class);
```

**Common usage in Laravel:**
- **Facades**: Abstractions over underlying implementations
- **Contracts**: Laravel's built-in interfaces (e.g., `Illuminate\Contracts\Mail\Mailer`)
- **Service Container**: Automatically resolves dependencies
- **Configuration**: Swap implementations via config files

```php
// Laravel provides contracts for all major components
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Contracts\Mail\Mailer;

class MyService
{
    public function __construct(
        private CacheRepository $cache,
        private Queue $queue,
        private Mailer $mailer
    ) {}
}

// Implementations resolved automatically based on config
// config/cache.php - switch 'redis' to 'memcached' 
// config/queue.php - switch 'database' to 'sqs'
// config/mail.php - switch 'smtp' to 'sendgrid'
// MyService doesn't need to change!
```

---

## Summary

### Part 1: Foundational Principles
- **DRY**: Eliminate duplication, centralize knowledge
- **KISS**: Simplicity over cleverness
- **YAGNI**: Build what you need now, not what you might need

### Part 2: SOLID Principles
- **S - Single Responsibility**: One class, one reason to change
- **O - Open/Closed**: Open for extension, closed for modification
- **L - Liskov Substitution**: Subtypes must be substitutable for their base types
- **I - Interface Segregation**: Many specific interfaces over one general interface
- **D - Dependency Inversion**: Depend on abstractions, not concretions

### The Laravel Way
Laravel embraces these principles deeply:
- **Skinny controllers** (SRP)
- **Service Providers** for binding implementations (DIP, OCP)
- **Contracts** (interfaces) throughout (DIP, ISP)
- **Events/Listeners** for decoupling (SRP, OCP)
- **Pipeline pattern** (OCP)
- **Policy classes** (SRP)
- **Form Requests** (SRP)
- **Jobs** (SRP)

**Remember:** Principles are guidelines, not laws. Apply them when they add value, not dogmatically. Write code that's maintainable, testable, and understandable. That's the real goal.
