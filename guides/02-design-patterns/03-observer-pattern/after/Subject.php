<?php

/**
 * Subject Interface - Observable objects implement this
 */

interface Subject
{
    public function attach(Observer $observer): void;
    public function detach(Observer $observer): void;
    public function notify(): void;
    public function getData(): array;
}
