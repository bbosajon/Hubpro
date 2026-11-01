@php($field = $paymentField ?? null)
<div class="border rounded-10 bg-white p-12 p-sm-20 field-row">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0 fw-bold text-capitalize">{{ translate('Field') }} <span class="field-no"></span></h6>
        <button type="button" class="btn btn-outline-danger icon-btn remove-input-fields-group" title="{{ translate('Delete') }}">
            <i class="fi fi-rr-trash"></i>
        </button>
    </div>
    <div class="row g-3">
        <div class="col-sm-6">
            <label class="form-label mb-2">{{ translate('Input field Name') }} <span class="text-danger">*</span></label>
            <input type="text" name="input_name[]" class="form-control"
                   placeholder="{{ translate('Ex').': '.translate('Bank Name') }}"
                   data-required-msg="{{ translate(':field is required', ['field' => translate('Input field Name')]) }}"
                   value="{{ $field ? trim(str_replace('_', ' ', $field['input_name'])) : '' }}" required>
        </div>
        <div class="col-sm-6">
            <label class="form-label mb-2">{{ translate('Input data') }} <span class="text-danger">*</span></label>
            <input type="text" name="input_data[]" class="form-control"
                   placeholder="{{ translate('Ex') }}: AVC bank"
                   data-required-msg="{{ translate(':field is required', ['field' => translate('Input data')]) }}"
                   value="{{ $field['input_data'] ?? '' }}" required>
        </div>
    </div>
</div>
