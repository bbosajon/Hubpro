<div class="card mb-3">
    <div class="card-body">
        <div class="row g-2 align-items-center">
            <div class="col-md-8 col-xl-9">
                <h3>{{ translate('Active clearance sale offer') }} ?</h3>
                <p class="m-0">
                    {{ translate('Show your offer on the store details page.') }}</p>
            </div>
            <div class="col-md-4 col-xl-3">
                <div class="d-flex justify-content-between align-items-center border rounded px-3 py-2">
                    <h5 class="mb-0 font-weight-normal">{{ translate('Active Offer') }}</h5>

                    <form action="{{ route('vendor.retail.clearance-sale.status-update') }}" data-from="clearance-sale"
                          method="post" id="clearance-sale-status-form" data-id="clearance-sale-status-form">
                        @csrf
                        <input type="hidden" name="status" value="0">
                        <label class="switcher mx-auto">
                            <input type="checkbox" class="switcher_input toggle-switch-message" value="1"
                                   {{  $clearanceConfig?->is_active == 1 ? 'checked':'' }}
                                   id="clearance-sale-status" name="status"
                                   data-modal-id="toggle-status-modal"
                                   data-toggle-id="clearance-sale-status"
                                   data-on-image="clearance-sale-on.png"
                                   data-off-image="clearance-sale-off.png"
                                   data-on-title="{{translate('Want to Turn ON :item', ['item' => translate('Clearance Sale')]).'?'}}"
                                   data-off-title="{{translate('Want to Turn OFF :item', ['item' => translate('Clearance Sale')]).'?'}}"
                                   data-on-message="<p>{{translate('Turning this on applies the clearance offer to your products.')}}</p>"
                                   data-off-message="<p>{{translate('Turning this off removes the clearance offer from your products.')}}</p>">
                            <span class="switcher_control"></span>
                        </label>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('vendor-views.promotion.clearance-sale.partials._clearance-sale-meta-setup')

<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('vendor.retail.clearance-sale.update-config') }}" method="POST">
            @csrf
            <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
                <h3 class="">{{ translate('Setup Offer Logics') }}</h3>

                @if($clearanceConfig)
                    <div class="text-primary fw-bold d-flex align-items-center gap-2 cursor-pointer"
                        data-toggle="offcanvas" data-target=".clearanceSaleMetaSetupOffcanvas">
                        {{ translate('Meta Data Setup') }}
                        <i class="fi fi-rr-arrow-right"></i>
                    </div>
                @endif
            </div>
            <div class="row g-2">
                <div class="col-lg-6">
                    <input type="hidden" name="setup_by" value="vendor">
                    <label class="form-label title-color font-weight-medium">{{ translate('Duration') }}</label>
                    <div class="position-relative">
                        <span class="tio-calendar icon-absolute-on-right"></span>
                        @if($clearanceConfig?->duration_start_date && $clearanceConfig?->duration_end_date)
                            <input type="text" class="js-daterangepicker-times-sec form-control line-1" name="clearance_sale_duration"
                                   value="{{ $clearanceConfig?->duration_start_date?->format('m/d/Y h:i:s A') }} - {{ $clearanceConfig?->duration_end_date?->format('m/d/Y h:i:s A') }}">
                        @else
                            <input type="text" class="js-daterangepicker-times-sec form-control line-1" name="clearance_sale_duration">
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <label class="form-label title-color font-weight-medium">{{ translate('Discount Type') }}</label>
                    <div class="form-control d-flex flex-wrap gap-2 h-auto">
                        <div class="custom-control custom-radio flex-grow-1">
                            <input type="radio" class="custom-control-input clearance-sale-discount" value="flat" name="discount_type"
                                   id="flat" {{ is_null($clearanceConfig) || $clearanceConfig?->discount_type == 'flat' ? 'checked' : '' }}>
                            <label class="custom-control-label w-auto" for="flat">
                                {{ translate('Flat Discount') }}
                            </label>
                        </div>
                        <div class="custom-control custom-radio flex-grow-1">
                            <input type="radio" class="custom-control-input clearance-sale-discount" value="product_wise" name="discount_type"
                                   id="product" {{ $clearanceConfig?->discount_type == 'product_wise' ? 'checked' : '' }}>
                            <label class="custom-control-label w-auto" for="product">
                                {{ translate('Product wise discount') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 clearance-sale-discount-flat {{ $clearanceConfig?->discount_type == 'product_wise' ? 'd--none' : '' }}">
                    <label class="form-label title-color font-weight-medium">
                        {{ translate('Discount Amount') }} (%)
                    </label>

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" min="0" pattern="[0-9]*"
                               name="discount_amount" placeholder="{{ translate('Ex') }} : {{ '10' }}"
                               value="{{ $clearanceConfig?->discount_amount ?? 0 }}" {{ is_null($clearanceConfig) || $clearanceConfig?->discount_type == 'product_wise' ? 'd--none' : '' }}>
                        <div class="input-group-append">
                            <span class="input-group-text font-weight-bolder" id="basic-addon2">{{ '%' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <label
                        class="form-label title-color font-weight-medium">
                        {{ translate('Offer Active Time') }}
                    </label>
                    <div class="form-control d-flex flex-wrap gap-2 h-auto">
                        <div class="custom-control custom-radio flex-grow-1">
                            <input type="radio" class="custom-control-input offer-active-time" value="always" name="offer_active_time"
                                   id="always" {{ is_null($clearanceConfig) || $clearanceConfig?->offer_active_time == 'always' ? 'checked' : '' }}>
                            <label class="custom-control-label w-auto" for="always">{{ translate('Always') }}</label>
                        </div>
                        <div class="custom-control custom-radio flex-grow-1">
                            <input type="radio" class="custom-control-input offer-active-time" value="specific_time" name="offer_active_time"
                                   id="specificTime" {{ $clearanceConfig?->offer_active_time == 'specific_time' ? 'checked' : '' }}>
                            <label class="custom-control-label w-auto" for="specificTime">
                                {{ translate('Specific time in a day') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 offer-active-time-section {{ $clearanceConfig?->offer_active_time != 'specific_time' ? 'd--none' : '' }}">
                    <label class="form-label title-color font-weight-medium">
                        {{ translate('Start & End Time') }}
                    </label>
                    <div class="position-relative">
                        <span class="tio-calendar icon-absolute-on-right"></span>
                        @if($clearanceConfig?->offer_active_range_start && $clearanceConfig?->offer_active_range_end)
                            <input type="text" class="js-daterangepicker-time-only form-control" name="offer_active_range"
                                   value="{{ $clearanceConfig?->offer_active_range_start?->format('h:i:s A') }} - {{ $clearanceConfig?->offer_active_range_end?->format('h:i:s A') }}">
                        @else
                            <input type="text" class="js-daterangepicker-time-only form-control" name="offer_active_range">
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 {{ session('direction') == 'rtl' ? 'mr-auto' : 'ml-auto' }}">
                    <label class="form-label d-none d-md-block">&nbsp;</label>
                    <div class="btn--container justify-content-end">
                        <a class="btn btn-secondary" href="{{ route('vendor.retail.clearance-sale.index') }}">
                            {{ translate('Reset') }}
                        </a>
                        @if($clearanceConfig)
                            <button class="btn btn--primary" type="submit">{{ translate('Update') }}</button>
                        @else
                            <button class="btn btn--primary" type="submit">{{ translate('Save') }}</button>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
