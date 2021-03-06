<?php

namespace App\Repositories\TwoFactorAuth;

use PragmaRX\Recovery\Recovery;

/**
 * Class RecoveryRepository
 *
 * @package App\Repositories\TwoFactorAuth
 */
class RecoveryRepository
{
    private Recovery $recovery;

    public function __construct(Recovery $recovery)
    {
        $this->recovery = $recovery;
    }

    public function generateTokens()
    {
        return $this->recovery->numeric()->setChars(10)->toArray();
    }
}
