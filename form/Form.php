<?php

namespace callmeliow\phpmvc\form;

use callmeliow\phpmvc\Model;

class Form
{
    public static function begin($action, $method)
    {
        echo sprintf('<form action="%s" method="%s">', $action, $method);
        return new Form();
    }

    public static function end()
    {
        return '</form>';
    }

    public function inputField(Model $model, $attribute, $label)
    {
        return new InputField($model, $attribute, $label);
    }

    public function textAreaField(Model $model, $attribute, $label)
    {
        return new TextareaField($model, $attribute, $label);
    }
}
