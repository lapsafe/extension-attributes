<?php

declare(strict_types=1);

namespace LapSafe\ExtensionAttributes\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
use LapSafe\ExtensionAttributes\Enums\AttributeType;
use LapSafe\ExtensionAttributes\ExtensionAttributeRegistrar;

/**
 * @property string $name
 * @property string $model_type
 * @property string $key
 * @property AttributeType $type
 */
class ExtensionAttribute extends Model
{
    protected $casts = [
        'type' => AttributeType::class,
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

    public function modelDescription(): Attribute
    {
        return Attribute::make(
            get: fn() => array_flip(config('extension-attributes.models'))[$this->model_type],
        );
    }

    public function delete(): ?bool
    {
        $model = isset(Relation::$morphMap[$this->model_type])
            ? Relation::$morphMap[$this->model_type]
            : $this->model_type;

        if (! is_subclass_of(object_or_class: $model, class: Model::class)) {
            $response = parent::delete();
            app(ExtensionAttributeRegistrar::class)->forgetCachedAttributes();

            return $response;
        }

        $model::query()
            ->update([
                'extension_attributes' => DB::raw("JSON_REMOVE(extension_attributes, '$.{$this->key}')"),
            ]);

        $response = parent::delete();
        app(ExtensionAttributeRegistrar::class)->forgetCachedAttributes();

        return $response;
    }
}
