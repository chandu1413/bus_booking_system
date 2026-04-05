<div class="{{ $divClass }}">
    <label class="{{ $lableClass }}">
        {{ $lable }}
        @if ($required)
            <span class="text-red-500">*</span>
        @endif
    </label>

    <input 
        type="text"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        id="{{ $id }}"
        pattern="[0-9]{12}"
        title="Enter valid Aadhaar number (12 digits)"
        class="{{ $inputClass }} @error($name) border-red-500 @enderror"
        inputmode="numeric"
        maxlength="12"
        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
        @if ($required) required @endif
        @if ($readonly) readonly @endif
        @if ($disabled) disabled @endif
        @if ($autofocus) autofocus @endif
        placeholder="{{ $placeholder }}"
    >

    <p class="text-red-500 text-sm mt-1 aadhar-error hidden">
        Invalid Aadhaar number (must be 12 digits)
    </p>

    @error($name)
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

@push('scripts')
@once
<script>
$(document).ready(function () {

    $('#{{ $id }}').on('input', function () {
        const regex = /^[0-9]{12}$/;
        let value = $(this).val();

        value = value.replace(/[^0-9]/g, '');
        $(this).val(value);

        let errorEl = $(this).closest('div').find('.aadhar-error');

        if (value.length === 12) {
            if (!regex.test(value)) {
                $(this).addClass('border-red-500');
                errorEl.removeClass('hidden');
            } else {
                $(this).removeClass('border-red-500');
                errorEl.addClass('hidden');
            }
        } else {
            $(this).removeClass('border-red-500');
            errorEl.addClass('hidden');
        }
    });

});
</script>
@endonce
@endpush