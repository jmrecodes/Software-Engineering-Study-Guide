<?php

/**
 * AFTER: The Solution with Factory Method Pattern
 * Product Interface - All reports must implement this
 */

interface Report
{
    public function generate(): string;
    public function getFormat(): string;
}
