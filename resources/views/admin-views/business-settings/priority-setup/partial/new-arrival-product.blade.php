<div class="card mt-2 new-arrival-product">
    <div class="card-body">
        <div class="mb-3 mb-sm-20">
            <h3>{{ translate('New Arrival Products') }}</h3>
            <p class="mb-0 fs-12">
                {{ translate('Recently added products with positive reviews and ratings.') }}
            </p>
        </div>
        <div class="p-12 p-sm-20 bg-section rounded">
            <div class="d-flex flex-column gap-20">
                <div class="bg-white p-3 rounded">
                    <div class="row g-4">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-check d-flex gap-2 gap-sm-3">
                                <input class="form-check-input radio--input radio--input_lg switcher-input-js"
                                       name="new_arrival_product_list_priority[custom_sorting_status]" value="0"
                                       type="radio" data-parent-class="new-arrival-product"
                                       data-from="default-sorting"
                                    {{ $newArrivalProductListPriority?->custom_sorting_status == 1 ? '' : 'checked' }}>
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
                                       type="radio" name="new_arrival_product_list_priority[custom_sorting_status]" value="1"
                                       data-parent-class="new-arrival-product" data-from="custom-sorting"
                                    {{ isset($newArrivalProductListPriority?->custom_sorting_status) && $newArrivalProductListPriority?->custom_sorting_status == 1 ? 'checked' : '' }}>
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
                    class="custom-sorting-radio-list {{ isset($newArrivalProductListPriority?->custom_sorting_status) && $newArrivalProductListPriority?->custom_sorting_status == 1 ? '' : 'd--none' }}">
                    <div class="d-flex flex-column gap-20">
                        <div>
                            <h3 class="mb-1">{{ translate('Set duration') }}</h3>
                            <p class="mb-0">{{ translate('Products are considered as') }} <span
                                    class="fw-semibold">{{ translate('New Arrival') }}</span>, {{ translate('If it is added within x days months') }}
                            </p>
                            <div class="input-group mt-2">
                                <input type="number" class="form-control" name="new_arrival_product_list_priority[duration]"
                                       min="1"
                                       placeholder="{{ translate('Ex') . ': 5' }}"
                                       value="{{ isset($newArrivalProductListPriority?->duration) ? $newArrivalProductListPriority->duration : 1 }}"
                                       required>
                                <div class="input-group-append select-wrapper">
                                    <select class="form-select shadow-none"
                                            name="new_arrival_product_list_priority[duration_type]">
                                        <option value="days"
                                            {{ isset($newArrivalProductListPriority?->duration_type) && $newArrivalProductListPriority?->duration_type == 'days' ? 'selected' : '' }}>
                                            {{ translate('Days') }}</option>
                                        <option value="month"
                                            {{ isset($newArrivalProductListPriority?->duration_type) && $newArrivalProductListPriority?->duration_type == 'month' ? 'selected' : '' }}>
                                            {{ translate('Month') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-3 gap-sm-4 border rounded-10 bg-white p-12 p-sm-20">

                            <div class="form-check d-flex gap-1">
                                <input type="radio" class="form-check-input radio--input show"
                                       name="new_arrival_product_list_priority[sort_by]"
                                       value="latest_created" id="new-arrival-product-sort-by-latest-created"
                                    {{ isset($newArrivalProductListPriority?->sort_by) && $newArrivalProductListPriority?->sort_by == 'latest_created' ? 'checked' : '' }}>
                                <label class="form-check-label" for="new-arrival-product-sort-by-latest-created">
                                    {{ translate('Sort By Latest Created') }}
                                </label>
                            </div>

                            <div class="form-check d-flex gap-1">
                                <input type="radio" class="form-check-input radio--input show"
                                       name="new_arrival_product_list_priority[sort_by]"
                                       value="reviews_count" id="new-arrival-product-sort-by-reviews-count"
                                    {{ isset($newArrivalProductListPriority?->sort_by) && $newArrivalProductListPriority?->sort_by == 'reviews_count' ? 'checked' : '' }}>
                                <label class="form-check-label" for="new-arrival-product-sort-by-reviews-count">
                                    {{ translate('Sort By Reviews Count') }}
                                </label>
                            </div>

                            <div class="form-check d-flex gap-1">
                                <input type="radio" class="form-check-input radio--input show"
                                       name="new_arrival_product_list_priority[sort_by]"
                                       value="rating" id="new-arrival-product-sort-by-ratings"
                                    {{ isset($newArrivalProductListPriority?->sort_by) && $newArrivalProductListPriority?->sort_by == 'rating' ? 'checked' : '' }}>
                                <label class="form-check-label" for="new-arrival-product-sort-by-ratings">
                                    {{ translate('Sort By Average Ratings') }}
                                </label>
                            </div>

                            <div class="form-check d-flex gap-1">
                                <input type="radio" class="form-check-input radio--input show"
                                       name="new_arrival_product_list_priority[sort_by]"
                                       value="a_to_z" id="new-arrival-product-alphabetic-order"
                                    {{ isset($newArrivalProductListPriority?->sort_by) && $newArrivalProductListPriority?->sort_by == 'a_to_z' ? 'checked' : '' }}>
                                <label class="form-check-label" for="new-arrival-product-alphabetic-order">
                                    {{ translate('Sort by Alphabetical') }}
                                    ({{ 'A ' . translate('To') . ' Z' }})
                                </label>
                            </div>

                            <div class="form-check d-flex gap-1">
                                <input type="radio" class="form-check-input radio--input show"
                                       name="new_arrival_product_list_priority[sort_by]"
                                       value="z_to_a" id="new-arrival-product-alphabetic-order-reverse"
                                    {{ isset($newArrivalProductListPriority?->sort_by) && $newArrivalProductListPriority?->sort_by == 'z_to_a' ? 'checked' : '' }}>
                                <label class="form-check-label" for="new-arrival-product-alphabetic-order-reverse">
                                    {{ translate('Sort by Alphabetical') }}
                                    ({{ 'Z ' . translate('To') . ' A' }})
                                </label>
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-3 gap-sm-4 border rounded-10 bg-white p-12 p-sm-20">
                            <div class="form-check d-flex gap-1">
                                <input type="radio" name="new_arrival_product_list_priority[out_of_stock_product]" value="desc"
                                       class="form-check-input radio--input" data-parent-class="new-arrival-product"
                                       id="new-arrival-product-stock-out-remove"
                                    {{ isset($newArrivalProductListPriority?->out_of_stock_product) && $newArrivalProductListPriority?->out_of_stock_product == 'desc' ? 'checked' : '' }}>
                                <label class="form-check-label" for="new-arrival-product-stock-out-remove">
                                    {{ translate('Show stock out products at the end of the list') }}
                                </label>
                            </div>
                            <div class="form-check d-flex gap-1">
                                <input type="radio" name="new_arrival_product_list_priority[out_of_stock_product]" value="hide"
                                       class="form-check-input radio--input" data-parent-class="new-arrival-product"
                                       id="new-arrival-product-stock-out-last"
                                    {{ isset($newArrivalProductListPriority?->out_of_stock_product) && $newArrivalProductListPriority?->out_of_stock_product == 'hide' ? 'checked' : '' }}>
                                <label class="form-check-label" for="new-arrival-product-stock-out-last">
                                    {{ translate('Remove Stock Out Products From The List') }}
                                </label>
                            </div>
                            <div class="form-check d-flex gap-1">
                                <input type="radio" name="new_arrival_product_list_priority[out_of_stock_product]"
                                       value="default"
                                       class="form-check-input radio--input" data-parent-class="new-arrival-product"
                                       id="new-arrival-product-stock-out-default"
                                    {{ isset($newArrivalProductListPriority?->out_of_stock_product) ? ($newArrivalProductListPriority?->out_of_stock_product == 'default' ? 'checked' : '') : 'checked' }}>
                                <label class="form-check-label" for="new-arrival-product-stock-out-default">
                                    {{ translate('None') }}
                                </label>
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-3 gap-sm-4 border rounded-10 bg-white p-12 p-sm-20">

                            <div class="form-check d-flex gap-1">
                                <input type="radio" name="new_arrival_product_list_priority[temporary_close_sorting]"
                                       value="desc"
                                       class="form-check-input radio--input" data-parent-class="new-arrival-product"
                                       id="new-arrival-product-temporary-close-last"
                                    {{ isset($newArrivalProductListPriority?->temporary_close_sorting) && $newArrivalProductListPriority?->temporary_close_sorting == 'desc' ? 'checked' : '' }}>
                                <label class="form-check-label" for="new-arrival-product-temporary-close-last">
                                    {{ translate('Show product at the end of the list if store is temporarily off') }}
                                </label>
                            </div>
                            <div class="form-check d-flex gap-1">
                                <input type="radio" name="new_arrival_product_list_priority[temporary_close_sorting]"
                                       value="hide"
                                       class="form-check-input radio--input" data-parent-class="new-arrival-product"
                                       id="new-arrival-product-temporary-close-remove"
                                    {{ isset($newArrivalProductListPriority?->temporary_close_sorting) ? ($newArrivalProductListPriority?->temporary_close_sorting == 'hide' ? 'checked' : '') : 'checked' }}>
                                <label class="form-check-label" for="new-arrival-product-temporary-close-remove">
                                    {{ translate('Remove Product From The List If Store Is Temporarily Off') }}
                                </label>
                            </div>
                            <div class="form-check d-flex gap-1">
                                <input type="radio" name="new_arrival_product_list_priority[temporary_close_sorting]"
                                       value="default"
                                       class="form-check-input radio--input" data-parent-class="new-arrival-product"
                                       id="new-arrival-product-temporary-close-default"
                                    {{ isset($newArrivalProductListPriority?->temporary_close_sorting) ? ($newArrivalProductListPriority?->temporary_close_sorting == 'default' ? 'checked' : '') : 'checked' }}>
                                <label class="form-check-label" for="new-arrival-product-temporary-close-default">
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
