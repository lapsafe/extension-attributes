<?php

declare(strict_types=1);

namespace LapSafe\ExtensionAttributes;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $model_type
 * @property string $key
 * @property ExtensionAttributeType $type
 */
class ExtensionAttribute extends Model
{
    protected $casts = [
        'type' => ExtensionAttributeType::class,
    ];

    protected $fillable = [
        'name',
        'model_type',
        'key',
        'type',
    ];

    public function cast(mixed $value): mixed
    {
        return $this->type->cast($value);
    }
}
