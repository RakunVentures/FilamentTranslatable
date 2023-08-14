<?php

namespace Getabed\FilamentTranslatable\Traits;

trait FillTranslatableFormAttibuttes
{
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $locales = app('translatable.locales')->all();
        $attributes = $this->record->translatedAttributes;
        $fields = [];

        foreach($locales as $locale) {
            foreach($attributes as $attribute) {
                $fields[$locale][$attribute] = $this->record->translate($locale)?->{$attribute};
            }
        }

        return array_merge($data, $fields);
    }
}
