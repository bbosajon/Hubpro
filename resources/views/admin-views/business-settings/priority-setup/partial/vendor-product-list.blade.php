<div class="card mt-2 vendor-product-list">
    <div class="card-body">
        <div class="mb-3 mb-sm-20">
            <h3 class="text-capitalize">{{ translate('Vendor Product List') }}</h3>
            <p class="mb-0 fs-12">
                {{ translate('Vendor products listed by most ordered.').', '.translate('Have good reviews & sorted alphabetically') }}
            </p>
        </div>
        <div class="p-12 p-sm-20 bg-section rounded">
            <div class="d-flex flex-column gap-20">
                <div class="bg-white p-3 rounded">
                    <div class="row g-4">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-check d-flex gap-2 gap-sm-3">
                                <input class="form-check-input radio--input radio--input_lg switcher-input-js"
                                       type="radio" data-parent-class="vendor-product-list"
                                       data-from="default-sorting" name="vendor_product_list_priority[custom_sorting_status]"
                                       value="0"
                                    {{ $vendorProductListPriority?->custom_sorting_status == 1 ? '' : 'checked' }}>
                                <div class="flex-grow-1">
                                    <label for="" class="form-label text-dark fw-semibold mb-1">
                                        {{ translate('Use Default Sorting List') }}
                                    </label>
                                    <p class="fs-12 mb-3">
                                        {{ translate('Currently sorting this section based on latest added') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6">
                            <div class="form-check d-flex gap-2 gap-sm-3">
                                <input class="form-check-input radio--input radio--input_lg switcher-input-js"
                                       type="radio" name="vendor_product_list_priority[custom_sorting_status]" value="1"
                                       data-parent-class="vendor-product-list" data-from="custom-sorting"
                                    {{ isset($vendorProductListPriority?->custom_sorting_status) && $vendorProductListPriority?->custom_sorting_status == 1 ? 'checked' : ''}}>
                                <div class="flex-grow-1">
                                    <label for="" class="form-label text-dark fw-semibold mb-1">
                                        {{ translate('Use Custom Sorting List') }}
                                    </label>
                                    <p class="fs-12 mb-3">
                                        {{ translate('You can sort this section in other ways') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="custom-sorting-radio-list {{ isset($vendorProductListPriority?->custom_sorting_status) && $vendorProductListPriority?->custom_sorting_status == 1 ? '' : 'd--none'}}">
                    <div class="d-flex flex-column gap-20">
                        <div class="d-flex flex-column gap-3 gap-sm-4 border rounded-10 bg-white p-12 p-sm-20">
                            <div class="form-check d-flex gap-1">
                                <input type="radio" class="form-check-input radio--input show"
                                       name="vendor_product_list_priority[sort_by]"
                                       value="most_order" id="vendor-list-sort-by-most-order"
                                    {{ isset($vendorProductListPriority?->sort_by) ? ($vendorProductListPriority?->sort_by == 'most_order' ? 'checked' : '') : 'checked'}}>
                                <label class="form-check-label text-capitalize"
                                       for="vendor-list-sort-by-most-order">
                                    {{ translate('Sort By Most Order') }}
                                </label>
                            </div>

                            <div class="form-check d-flex gap-1">
                                <input type="radio" class="form-check-input radio--input show"
                                       name="vendor_product_list_priority[sort_by]"
                                       id="vendor-list-sort-by-reviews-count" value="reviews_count"
                                    {{ isset($vendorProductListPriority?->sort_by) && $vendorProductListPriority?->sort_by == 'reviews_count' ? 'checked' : ''}}>
                                <label class="form-check-label text-capitalize"
                                       for="vendor-list-sort-by-reviews-count">
                                    {{ translate('Sort By Reviews Count') }}
                                </label>
                            </div>

                            <div class="form-check d-flex gap-1">
                                <input type="radio" class="form-check-input radio--input show"
                                       name="vendor_product_list_priority[sort_by]"
                                       id="vendor-list-sort-by-ratings" value="rating"
                                    {{ isset($vendorProductListPriority?->sort_by) && $vendorProductListPriority?->sort_by == 'rating' ? 'checked' : ''}}>
                                <label class="form-check-label text-capitalize" for="vendor-list-sort-by-ratings">
                                    {{ translate('Sort By Average Ratings') }}
                                </label>
                            </div>

                            <div class="form-check d-flex gap-1">
                                <input type="radio" class="form-check-input radio--input show"
                                       name="vendor_product_list_priority[sort_by]"
                                       value="a_to_z" id="vendor-list-alphabetic-order"
                                    {{ isset($vendorProductListPriority?->sort_by) && $vendorProductListPriority?->sort_by == 'a_to_z' ? 'checked' : ''}}>
                                <label class="form-check-label text-capitalize" for="vendor-list-alphabetic-order">
                                    {{ translate('Sort by Alphabetical') }}
                                    ({{ 'A ' . translate('To') . ' Z' }})
                                </label>
                            </div>
                            <div class="form-check d-flex gap-1">
                                <input type="radio" class="form-check-input radio--input show"
                                       name="vendor_product_list_priority[sort_by]"
                                       value="z_to_a" id="vendor-list-alphabetic-order-reverse"
                                    {{ isset($vendorProductListPriority?->sort_by) && $vendorProductListPriority?->sort_by == 'z_to_a' ? 'checked' : ''}}>
                                <label class="form-check-label text-capitalize"
                                       for="vendor-list-alphabetic-order-reverse">
                                    {{ translate('Sort by Alphabetical') }}
                                    ({{ 'Z ' . translate('To') . ' A' }})
                                </label>
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-3 gap-sm-4 border rounded-10 bg-white p-12 p-sm-20">

                            <div class="form-check d-flex gap-1">
                                <input type="radio" class="form-check-input radio--input show"
                                       name="vendor_product_list_priority[out_of_stock_product]" value="desc"
                                       data-parent-class="vendor-product-list" id="vendor-list-stock-out-remove"
                                    {{ isset($vendorProductListPriority?->out_of_stock_product) && $vendorProductListPriority?->out_of_stock_product == 'desc' ? 'checked' : ''}}>
                                <label class="form-check-label text-capitalize" for="vendor-list-stock-out-remove">
                                    {{ translate('Show stock out products at the end of the list') }}
                                </label>
                            </div>

                            <div class="form-check d-flex gap-1">
                                <input type="radio" class="form-check-input radio--input show"
                                       name="vendor_product_list_priority[out_of_stock_product]" value="hide"
                                       data-parent-class="vendor-product-list" id="vendor-list-stock-out-show"
                                    {{ isset($vendorProductListPriority?->out_of_stock_product) && $vendorProductListPriority?->out_of_stock_product == 'hide' ? 'checked' : ''}}>
                                <label class="form-check-label text-capitalize" for="vendor-list-stock-out-show">
                                    {{ translate('Remove Stock Out Products From The List') }}
                                </label>
                            </div>

                            <div class="form-check d-flex gap-1">
                                <input type="radio" class="form-check-input radio--input show"
                                       name="vendor_product_list_priority[out_of_stock_product]" value="default"
                                       data-parent-class="vendor-product-list" id="vendor-list-stock-out-none"
                                    {{ isset($vendorProductListPriority?->out_of_stock_product) ? ($vendorProductListPriority?->out_of_stock_product == 'default' ? 'checked' : '') :'checked'}}>
                                <label class="form-check-label text-capitalize" for="vendor-list-stock-out-none">
                                    {{ translate('None') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
