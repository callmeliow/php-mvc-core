<?php

namespace callmeliow\phpmvc\form;

use callmeliow\phpmvc\Model;

class TextareaField extends BaseField
{
    public string $label;

    public function __construct(Model $model, string $attribute, string $label)
    {
        $this->label = $label;
        parent::__construct($model, $attribute);
    }

    public function renderInput(): string
    {
        return sprintf(
            '<textarea name="%s" class="form-control%s">%s</textarea>',
            $this->attribute,
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->model->{$this->attribute},
        );
    }
}
