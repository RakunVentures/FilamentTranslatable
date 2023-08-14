<?php

namespace Getabed\FilamentTranslatable\Components;

use Closure;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;

class TranslatableFields extends Tabs
{
    private ?array $fields = null;
    private ?array $locales = null;

    public function fields(array | Closure $fields): static
    {
        $this->fields = $fields;

        $tabs = $this->makeTabs();

        return static::make($this->label)
            ->tabs($tabs);
    }

    public function locales(array $locales): static
    {
        $this->locales = $locales;

        return $this;

    }

    private function makeTabs(): array
    {
        $tabs = [];

        foreach($this->getLocales() as $locale) {
            $tabs[] = $this->makeTab($locale)
                ->schema($this->makeFields($locale));
        }

        return $tabs;
    }

    private function getLocales(): array
    {
        return $this->locales ?: app('translatable.locales')->all();
    }

    private function makeTab(string $locale): Tab
    {
        $label = ucfirst($locale);
        return Tab::make($label);
    }

    private function makeFields(string $locale): array
    {
        $fields = [];
        foreach($this->fields as $field) {
            $name = $locale.'.'.$field->getName();
            $newField = clone $field;
            $fields[] = $newField->name($name)->statePath($name);
        }

        return $fields;
    }


}
