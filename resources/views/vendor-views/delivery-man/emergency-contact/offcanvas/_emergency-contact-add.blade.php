<form action="{{ route('vendor.delivery-man.emergency-contact.index') }}" method="post">
    @csrf
    <div class="offcanvas-sidebar" id="emergencyContactAddOffcanvas">
        <div class="offcanvas-overlay" data-dismiss="offcanvas"></div>

        <div class="offcanvas-content bg-white shadow d-flex flex-column">
            <div class="offcanvas-header bg-light d-flex justify-content-between align-items-center p-3">
                <h3 class="text-capitalize m-0">{{ translate('Add new contact') }}</h3>
                <button type="button" class="close" data-dismiss="offcanvas" aria-label="{{ translate('Close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="offcanvas-body p-3 overflow-auto flex-grow-1">
                <div class="p-12 p-sm-20 bg-section rounded">
                    <div class="form-group">
                        <label class="title-color d-flex" for="emergency-contact-add-name">
                            {{ translate('Contact name') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" class="form-control" id="emergency-contact-add-name"
                               value="{{ old('name') }}"
                               placeholder="{{ translate('Contact name') }}" required>
                    </div>
                    <div class="form-group mb-0">
                        <label class="title-color d-flex" for="emergency-contact-add-phone">
                            {{ translate('Phone') }} <span class="text-danger">*</span>
                        </label>
                        <input type="tel" name="phone" class="form-control" id="emergency-contact-add-phone"
                               value="{{ old('phone') }}"
                               placeholder="{{ translate('Ex').':'.'017********' }}" required>
                    </div>
                </div>
            </div>

            <div class="offcanvas-footer offcanvas-footer-sticky p-3 border-top bg-white d-flex gap-3">
                <button type="reset" class="btn btn-secondary w-100">{{ translate('Reset') }}</button>
                <button type="submit" class="btn btn--primary w-100">{{ translate('Submit') }}</button>
            </div>
        </div>
    </div>
</form>
