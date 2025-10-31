Home / Design Patterns / Structural / Facade

# Facade Pattern

> **One-line summary**: Expose a simplified interface that orchestrates complex subsystems behind the scenes.

## Table of Contents
- [Overview](#overview)
- [When to Use](#when-to-use)
- [When NOT to Use](#when-not-to-use)
- [Implementation](#implementation)
- [Laravel Example](#laravel-example)
- [Testing](#testing)
- [Common Pitfalls](#common-pitfalls)
- [Trade-offs](#trade-offs)
- [Exercises](#exercises)
- [See Also](#see-also)

## Overview
Facades consolidate multiple services into a single entry point, improving readability and providing guardrails around complex workflows.

## When to Use
- APIs that orchestrate multiple services (payment + invoicing + email).
- Simplifying onboarding for complex domain logic.

## When NOT to Use
- When the facade simply proxies without adding clarity.
- When direct access to subsystem features is necessary.

## Implementation
```php
final class CheckoutFacade
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly PaymentGateway $gateway,
        private readonly ReceiptGenerator $receipts,
        private readonly Mailer $mailer
    ) {}

    public function checkout(int $cartId): Receipt
    {
        $cart = $this->cartService->load($cartId);
        $payment = $this->gateway->charge($cart->total());
        $receipt = $this->receipts->create($cart, $payment);

        $this->mailer->send(new ReceiptMail($receipt));

        return $receipt;
    }
}
```

## Laravel Example
- Register facades in service providers for reuse within controllers or jobs.
- Document subsystem interactions inside the facade class to aid maintainability.

## Testing
- Unit test facade orchestration with mocks for subsystems.
- Feature test the checkout flow to ensure real integrations work together.

## Common Pitfalls
- [ ] Facades that grow without boundaries—monitor responsibilities.
- [ ] Skipping validation because "the facade handles it" without explicit checks.

## Trade-offs
- Simplifies consumption but can hide complexity; keep logs or traces for observability.

## Exercises
1. Build a facade around report generation (data fetch + format + delivery).
2. Add structured logging inside the facade and assert entries in integration tests.

## See Also
- [`../../02-solid/single-responsibility.md`](../../02-solid/single-responsibility.md)
- [`decorator-pattern.md`](decorator-pattern.md)
- [`../behavioral/command-pattern.md`](../behavioral/command-pattern.md)

---
[Previous: Adapter Pattern](adapter-pattern.md) | [Home](../../README.md) | [Next: Strategy Pattern](../behavioral/strategy-pattern.md)
