<?php

namespace App\Enums;

enum OperatorStatus: string
{
    case SUBMITTED = 'submitted';
    case PENDING   = 'pending';
    case REJECTED  = 'rejected';
    case APPROVED  = 'approved';
    case SUSPENDED = 'suspended';

    public function isSubmitted(): bool
    {
        return $this === self::SUBMITTED;
    }

    public function needsUpdate(): bool
    {
        return $this === self::PENDING || $this === self::SUSPENDED;
    }
    
    public function isApproved(): bool
    {
        return $this === self::APPROVED;
    }
    
    public function isPending(): bool
    {
        return $this === self::PENDING;
    }
    
    public function isSuspended(): bool
    {
        return $this === self::SUSPENDED;
    }
}