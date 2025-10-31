<?php

/**
 * AFTER: The Solution with Observer Pattern
 * Observer Interface - All observers must implement this
 */

interface Observer
{
    public function update(Subject $subject): void;
    public function getName(): string;
}
