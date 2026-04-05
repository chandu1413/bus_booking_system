<div class="{{ $divClass }}">
    
    <label class="{{ $labelClass }}">
        {{ $label }}
        @if ($required)
            <span class="text-red-500">*</span>
        @endif
    </label>

    <input 
        type="file"
        name="{{ $multiple ? $name.'[]' : $name }}"
        id="{{ $id }}"
        class="{{ $inputClass }} @error($name) border-red-500 @enderror"
        @if ($required) required @endif
        @if ($multiple) multiple @endif
        @if ($accept) accept="{{ $accept }}" @endif
    >

    {{-- File preview name --}}
    <p class="text-sm text-gray-500 mt-1 file-name hidden"></p>

    @error($name)
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror

</div>