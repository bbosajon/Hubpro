@php($field = $customerField ?? null)
@php($fieldKey = $customerKey ?? 0)
<div class="border rounded-10 bg-white p-12 p-sm-20 field-row">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0 fw-bold text-capitalize">{{ translate('Field') }} <span class="field-no"></span></h6>
        <button type="button" class="btn btn-outline-danger icon-btn remove-input-fields-group" title="{{ translate('Delete') }}">
            <i class="fi fi-rr-trash"></i>
        </button>
    </div>
    <div class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label mb-2">{{ translate('Input field Name') }} <span class="text-danger">*</span></label>
            <input type="text" name="customer_input[]" class="form-control"
                   placeholder="{{ translate('Ex').': '.translate('Payment By') }}"
                   data-required-msg="{{ translate(':field is required', ['field' => translate('Input field Name')]) }}"
                   value="{{ $field ? trim(str_replace('_', ' ', $field['customer_input'])) : '' }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label mb-2">{{ translate('Placeholder') }} <span class="text-danger">*</span></label>
            <input type="text" name="customer_placeholder[]" class="form-control"
                   placeholder="{{ translate('Ex').': '.translate('Enter name') }}"
                   data-required-msg="{{ translate('Placeholder field is required') }}"
                   value="{{ $field['customer_placeholder'] ?? '' }}" required>
        </div>
        <div class="col-md-4">
            <label class="d-flex align-items-center gap-2 border rounded px-3 min-h-40 mb-0 user-select-none cursor-pointer">
                <input type="checkbox" class="form-check-input checkbox--input mt-0" value="1"
                       name="is_required[{{ $fieldKey }}]" {{ ($field && ($field['is_required'] ?? 0)) ? 'checked' : '' }}>
                <span class="text-muted">{{ translate('Is required') }}</span>
            </label>
        </div>
    </div>
</div>
