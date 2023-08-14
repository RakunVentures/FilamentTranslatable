<?php

namespace Getabed\FilamentTranslatable\Traits;

use Astrotomic\Translatable\Translatable as AstrotomicTranslatable;

trait Translatable
{
    use AstrotomicTranslatable;

    public function getAttribute($key)
    {
        if($key != null) {
            [$attribute, $locale] = $this->getAttributeAndLocale($key);

            if ($this->isTranslationAttribute($attribute)) {
                if ($this->getTranslation($locale) === null) {
                    return $this->getAttributeValue($attribute);
                }

                // If the given $attribute has a mutator, we push it to $attributes and then call getAttributeValue
                // on it. This way, we can use Eloquent's checking for Mutation, type casting, and
                // Date fields.
                if ($this->hasGetMutator($attribute)) {
                    $this->attributes[$attribute] = $this->getAttributeOrFallback($locale, $attribute);

                    return $this->getAttributeValue($attribute);
                }

                return $this->getAttributeOrFallback($locale, $attribute);
            }
        }

        return parent::getAttribute($key);
    }
}
