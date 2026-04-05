<div class="{{ $divClass ?? '' }}">
    <label class="{{ $lableClass }}">{{ $lable }} 
        @if ($required ?? false)
            <span class="text-red-500">*</span>
        @endif  
    </label>
    <input 
        type="text"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
         id="{{ $id }}" 
        pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}"
        title="{{ $lable }}"
        class="{{ $inputClass }} @error($name) border-red-500 @enderror"
        oninput="this.value = this.value.toUpperCase()" 
        @if ($required ?? false) required @endif
         @if ($readonly ?? false) readonly @endif
         @if ($disabled ?? false) disabled @endif
         @if ($autofocus ?? false) autofocus @endif
         @if ($placeholder) placeholder="{{ $placeholder }}" @endif
         @if ($minlength) minlength="{{ $minlength }}" @endif
         @if ($maxlength) maxlength="{{ $maxlength }}" @endif
    >
    <p class="text-red-500 text-sm mt-1 pan-error hidden">
    Invalid PAN format (Example: ABCDE1234F)
</p>

    @error($name)
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

@push('scripts')
@once
<script>
$(document).ready(function () {

    $('input[name="{{ $name }}"]').on('input', function () {
        const regex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
        let value = $(this).val().toUpperCase();

        $(this).val(value);

        let errorEl = $(this).closest('div').find('.pan-error');

        // Reset state
        $(this).removeClass('border-red-500');
        errorEl.addClass('hidden');

        // Validate only when full length
        if (value.length === 10) {
            if (!regex.test(value)) {
                $(this).addClass('border-red-500');
                errorEl.removeClass('hidden'); 
            }
        }
    });

});
</script>
@endonce
@endpush