<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputFileUpload extends Component
{
    public $name, $id, $label;
    public $divClass, $labelClass, $inputClass;
    public $required, $multiple, $accept;

    public function __construct(
        $name = '',
        $id = '',
        $label = '',
        $divClass = '',
        $labelClass = ' block text-sm font-medium text-gray-700 mb-1',
        $inputClass = '',
        $required = false,
        $multiple = false,
        $accept = 'images/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ) {
        $this->name = $name;
        $this->id = $id ?? $name;
        $this->label = $label;
        $this->divClass = $divClass;
        $this->labelClass = $labelClass;
        $this->inputClass = $inputClass;
        $this->required = $required;
        $this->multiple = $multiple;
        $this->accept = $accept;
    }

    public function render()
    {
        return view('components.input-file-upload');
    }
}