# Design Patterns Cheat Sheet

## Quick Pattern Selection

```
Need to swap algorithms? → Strategy Pattern
Need to create varied objects? → Factory Method Pattern
Need to notify multiple objects? → Observer Pattern
Integrating incompatible APIs? → Adapter Pattern
Need to add features dynamically? → Decorator Pattern
```

## Running the Examples

```bash
# From project root
php "Design Patterns for the Modern PHP & Laravel Developer/[pattern]/php-example/demo.php"
```

## Pattern Signatures

### Strategy Pattern
```php
interface Strategy { public function execute(); }
class ConcreteStrategy implements Strategy { /* ... */ }
class Context {
    public function __construct(private Strategy $strategy) {}
}
```

### Factory Method Pattern
```php
interface Product { /* ... */ }
abstract class Creator {
    abstract protected function createProduct(): Product;
}
class ConcreteCreator extends Creator { /* ... */ }
```

### Observer Pattern
```php
interface Observer { public function update(Subject $subject); }
interface Subject {
    public function attach(Observer $o);
    public function notify();
}
```

### Adapter Pattern
```php
interface Target { public function request(); }
class Adapter implements Target {
    public function __construct(private Adaptee $adaptee) {}
    public function request() { return $this->adaptee->specificRequest(); }
}
```

### Decorator Pattern
```php
interface Component { public function operation(); }
abstract class Decorator implements Component {
    public function __construct(protected Component $component) {}
}
class ConcreteDecorator extends Decorator { /* ... */ }
```

## Laravel Examples

### Strategy - Payment Gateways
```php
interface PaymentStrategy {}
class StripePayment implements PaymentStrategy {}
// Inject via constructor or service container
```

### Factory Method - Reports
```php
abstract class ReportFactory {
    abstract protected function createReport(): Report;
}
```

### Observer - Model Events
```php
// app/Observers/UserObserver.php
class UserObserver {
    public function created(User $user) { /* ... */ }
}
```

### Adapter - SMS Providers
```php
interface SmsInterface {}
class TwilioAdapter implements SmsInterface {}
// Bind in service provider
```

### Decorator - Middleware
```php
class CustomMiddleware {
    public function handle($request, Closure $next) {
        // Before
        $response = $next($request);
        // After
        return $response;
    }
}
```

## SOLID Principles Checklist

- **S**ingle Responsibility - One class, one job
- **O**pen/Closed - Open for extension, closed for modification
- **L**iskov Substitution - Subtypes are substitutable
- **I**nterface Segregation - Many specific interfaces
- **D**ependency Inversion - Depend on abstractions

## When NOT to Use Patterns

❌ Only one algorithm → Skip Strategy  
❌ Simple object creation → Skip Factory  
❌ Only one observer → Skip Observer  
❌ You control both interfaces → Skip Adapter  
❌ Static features only → Skip Decorator  

## Common Mistakes

🚫 Using patterns for the sake of using them  
🚫 Over-engineering simple problems  
🚫 Not understanding the problem first  
🚫 Mixing pattern responsibilities  
🚫 Ignoring Laravel's built-in solutions  

## Testing Patterns

```php
// Strategy
$mock = $this->createMock(PaymentStrategy::class);

// Factory
$factory = new TestReportFactory();

// Observer
$observer = $this->createMock(Observer::class);
$subject->attach($observer);

// Adapter
$mockAdaptee = $this->createMock(Adaptee::class);
$adapter = new Adapter($mockAdaptee);

// Decorator
$mock = $this->createMock(Component::class);
$decorator = new ConcreteDecorator($mock);
```

## File Structure Best Practices

```
app/
├── Contracts/          # Interfaces
├── Services/          # Business logic
│   └── Payment/       # Strategies
├── Factories/         # Factory classes
├── Observers/         # Observer classes
└── Adapters/          # Adapter classes
```

## Quick Reference Commands

```bash
# Test all patterns
for f in */php-example/demo.php; do php "$f"; done

# Find pattern usage in Laravel
grep -r "implements.*Strategy" vendor/laravel

# Create new pattern implementation
mkdir -p app/Services/MyPattern/{Contracts,Implementations}
```

---

Keep this cheat sheet handy while learning! 🚀
