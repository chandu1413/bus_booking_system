<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputAadharNo extends Component
{
    public $name, $value, $id;
    public $divClass, $lableClass, $lable, $inputClass;
    public $required, $readonly, $disabled, $autofocus, $placeholder;

    public function __construct(
        $name = 'aadhar',
        $value = '',
        $id = null,
        $divClass = '',
        $lableClass = '',
        $lable = 'Aadhaar Number',
        $inputClass = '',
        $required = false,
        $readonly = false,
        $disabled = false,
        $autofocus = false,
        $placeholder = 'Enter Aadhaar Number'
    ) {
        $this->name = $name;
        $this->value = $value;
        $this->id = $id ?? $name;
        $this->divClass = $divClass;
        $this->lableClass = $lableClass;
        $this->lable = $lable;
        $this->inputClass = $inputClass;
        $this->required = $required;
        $this->readonly = $readonly;
        $this->disabled = $disabled;
        $this->autofocus = $autofocus;
        $this->placeholder = $placeholder;
    }

    public function render()
    {
        return view('components.input-aadhar-no');
    }
}