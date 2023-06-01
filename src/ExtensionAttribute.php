<?php

declare(strict_types=1);

namespace LapSafe\ExtensionAttributes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;

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

    public function delete(): ?bool
    {
        $model = isset(Relation::$morphMap[$this->model_type])
            ? Relation::$morphMap[$this->model_type]
            : $this->model_type;

        if(! is_subclass_of(object_or_class: $model, class: Model::class)) {
            $response = parent::delete();
            app(ExtensionAttributeRegistrar::class)->forgetCachedAttributes();
            return $response;
        }

        $model::query()
            ->update([
                'extension_attributes' => DB::raw("JSON_REMOVE(extension_attributes, '$.{$this->key}')")
            ]);

        $response = parent::delete();
        app(ExtensionAttributeRegistrar::class)->forgetCachedAttributes();

        return $response;
    }
}
