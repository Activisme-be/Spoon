<?php 

namespace App\Repositories; 

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class UserRepository 
 * 
 * @package App\Repositories
 */
class userRepository extends Authenticatable
{
    /**
     * Method for securing the request by confirming the password hash. 
     * 
     * @param  string $password The authenticated user password; 
     * @return bool 
     */
    public function securedRequest(string $password): bool 
    {
        return Hash::check($password, $this->getAuthPassword());
    }
}