<?php

namespace LapSafe\ExtensionAttributes\Tests\TestModels;

use Illuminate\Database\Eloquent\Model;
use LapSafe\ExtensionAttributes\Concerns\HasExtensionAttributes;

class User extends Model
{
    use HasExtensionAttributes;
}
