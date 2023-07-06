<?php

namespace App\Contracts;

interface DynamicTabContract
{
    public function submitRow(int $index): void;

    public function addRow(): void;

    public function removeRow(int $index): void;
}
