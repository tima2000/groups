<?php

namespace tima2000\Groups\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use tima2000\Groups\Traits\GroupHelpers;

class User extends Authenticatable
{
    use GroupHelpers;
}
