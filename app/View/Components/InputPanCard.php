<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputPanCard extends Component
{
    public $name;
    public $value;
    public $divClass;
    public $lable;
    public $lableClass;
    public $inputClass;
    public $readonly;
    public $required;
    public $title;
    public $disabled;
    public $autofocus;
    public $placeholder;
    public $id;
    public $minlength;
    public $maxlength;

    public function __construct(
        $name = '',
        $value = '',
        $inputClass = 'w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500',
        $lable = 'PAN Card Number',
        $lableClass = 'block text-sm font-medium text-gray-700 mb-1',
        $divClass = '',
        $readonly = false,
        $required = false,
        $title = 'Enter valid PAN (e.g. ABCDE1234F)',
        $disabled = false,
        $autofocus = false,
        $placeholder = '',
        $id = '',
        $minlength = '',
        $maxlength = 10,
    ) {
        $this->name = $name;
        $this->id = $id ?: $name;
        $this->minlength = $minlength;
        $this->maxlength = $maxlength;
        $this->value = strtoupper((string) $value);
        $this->inputClass = $inputClass;
        $this->lable = $lable;
        $this->lableClass = $lableClass;
        $this->divClass = $divClass;
        $this->readonly = $readonly;
        $this->required = $required;
        $this->title = $title;
        $this->disabled = $disabled;
        $this->autofocus = $autofocus;
        $this->placeholder = $placeholder;
    }

    public function render()
    {
        return view('components.input-pan-card');
    }
}
