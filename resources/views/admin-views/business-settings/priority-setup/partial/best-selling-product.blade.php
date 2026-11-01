<div class="card mt-2 best-selling-product">
    <div class="card-body">
        <div class="mb-3 mb-sm-20">
            <h3>{{ translate('Best Selling Products') }}</h3>
            <p class="mb-0 fs-12">
                {{ translate('Products bought most often in a given period.') }}
            </p>
        </div>
        <div class="p-12 p-sm-20 bg-section rounded">
            <div class="d-flex flex-column gap-20">
                <div class="bg-white p-3 rounded">
                    <div class="row g-4">
                        <div class="col-xl-6 col-md-6">
                            <div class="form-check d-flex gap-2 gap-sm-3">
                                <input class="form-check-input radio--input radio--input_lg switcher-input-js"
                                       type="radio" data-parent-class="best-selling-product"
                                       data-from="default-sorting" name="best_selling_product_list_priority[custom_sorting_status]" value="0"
                                    {{ $bestSellingProductListPriority?->custom_sorting_status == 1 ? '' : 'checked' }}>
                                <div class="flex-grow-1">
                                    <label for="" class="form-label text-dark fw-semibold mb-1">
                                        {{ translate('Use Default Sorting List') }}
                                    </label>
                                    <p class="fs-12 mb-3">
                                        {{ translate('Currently sorting this section based on order count') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6">
                            <div class="form-check d-flex gap-2 gap-sm-3">
                                <input class="form-check-input radio--input radio--input_lg switcher-input-js"
                                       type="radio" name="best_selling_product_list_priority[custom_sorting_status]" value="1"
                                       data-parent-class="best-selling-product" data-from="custom-sorting"
                                    {{ isset($bestSellingProductListPriority?->custom_sorting_status) && $bestSellingProductListPriority?->custom_sorting_status == 1 ? 'checked' : '' }}>
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
                    class="custom-sorting-radio-list {{ isset($bestSellingProductListPriority?->custom_sorting_status) && $bestSellingProductListPriority?->custom_sorting_status == 1 ? '' : 'd--none' }}">
                    <div class="d-flex flex-column gap-20">
                        <div class="d-flex flex-column gap-3 gap-sm-4 border rounded-10 bg-white p-12 p-sm-20">

                            <div class="form-check d-flex gap-1">
                                <input type="radio" class="form-check-input radio--input show" name="best_selling_product_list_priority[sort_by]"
                                       value="most_order" id="best-selling-product-sort-by-most-order"
                                    {{ isset($bestSellingProductListPriority?->sort_by) ? ($bestSellingProductListPriority?->sort_by == 'most_order' ? 'checked' : '') : 'checked' }}>
                                <label class="form-check-label" for="best-selling-product-sort-by-most-order">
                                    {{ translate('Sort By Most Order') }}
                                </label>
                            </div>

                            <div class="form-check d-flex gap-1">
                                <input type="radio" class="form-check-input radio--input show" name="best_selling_product_list_priority[sort_by]"
                                       value="reviews_count" id="best-selling-product-sort-by-reviews-count"
                                    {{ isset($bestSellingProductListPriority?->sort_by) && $bestSellingProductListPriority?->sort_by == 'reviews_count' ? 'checked' : '' }}>
                                <label class="form-check-label" for="best-selling-product-sort-by-reviews-count">
                                    {{ translate('Sort By Reviews Count') }}
                                </label>
                            </div>

                            <div class="form-check d-flex gap-1">
                                <input type="radio" class="form-check-input radio--input show" name="best_selling_product_list_priority[sort_by]"
                                       value="rating" id="best-selling-product-sort-by-ratings"
                                    {{ isset($bestSellingProductListPriority?->sort_by) && $bestSellingProductListPriority?->sort_by == 'rating' ? 'checked' : '' }}>
                                <label class="form-check-label" for="best-selling-product-sort-by-ratings">
                                    {{ translate('Sort By Average Ratings') }}
                                </label>
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-3 gap-sm-4 border rounded-10 bg-white p-12 p-sm-20">

                            <div class="form-check d-flex gap-1">
                                <input type="radio" class="form-check-input radio--input show"
                                       name="best_selling_product_list_priority[out_of_stock_product]" value="desc"
                                       data-parent-class="best-selling-product"
                                       id="best-selling-product-stock-out-remove"
                                    {{ isset($bestSellingProductListPriority?->out_of_stock_product) && $bestSellingProductListPriority?->out_of_stock_product == 'desc' ? 'checked' : '' }}>
                                <label class="form-check-label" for="best-selling-product-stock-out-remove">
                                    {{ translate('Show stock out products at the end of the list') }}
                                </label>
                            </div>

                            <div class="form-check d-flex gap-1">
                                <input type="radio" class="form-check-input radio--input show"
                                       name="best_selling_product_list_priority[out_of_stock_product]" value="hide"
                                       data-parent-class="best-selling-product"
                                       id="best-selling-product-stock-out-last"
                                    {{ isset($bestSellingProductListPriority?->out_of_stock_product) && $bestSellingProductListPriority?->out_of_stock_product == 'hide' ? 'checked' : '' }}>
                                <label class="form-check-label" for="best-selling-product-stock-out-last">
                                    {{ translate('Remove Stock Out Products From The List') }}
                                </label>
                            </div>

                            <div class="form-check d-flex gap-1">
                                <input type="radio" class="form-check-input radio--input show"
                                       name="best_selling_product_list_priority[out_of_stock_product]" value="default"
                                       data-parent-class="best-selling-product"
                                       id="best-selling-product-stock-out-default"
                                    {{ isset($bestSellingProductListPriority?->out_of_stock_product) ? ($bestSellingProductListPriority?->out_of_stock_product == 'default' ? 'checked' : '') :'checked' }}>
                                <label class="form-check-label" for="best-selling-product-stock-out-default">
                                    {{ translate('None') }}
                                </label>
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-3 gap-sm-4 border rounded-10 bg-white p-12 p-sm-20">

                            <div class="form-check d-flex gap-1">
                                <input type="radio" class="form-check-input radio--input show"
                                       name="best_selling_product_list_priority[temporary_close_sorting]" value="desc"
                                       data-parent-class="best-selling-product"
                                       id="best-selling-product-temporary-close-last"
                                    {{ isset($bestSellingProductListPriority?->temporary_close_sorting) && $bestSellingProductListPriority?->temporary_close_sorting == 'desc' ? 'checked' : '' }}>
                                <label class="form-check-label" for="best-selling-product-temporary-close-last">
                                    {{ translate('Show product at the end of the list if store is temporarily off') }}
                                </label>
                            </div>

                            <div class="form-check d-flex gap-1">
                                <input type="radio" class="form-check-input radio--input show"
                                       name="best_selling_product_list_priority[temporary_close_sorting]" value="hide"
                                       data-parent-class="best-selling-product"
                                       id="best-selling-product-temporary-close-remove"
                                    {{ isset($bestSellingProductListPriority?->temporary_close_sorting) ? ($bestSellingProductListPriority?->temporary_close_sorting == 'hide' ? 'checked' : '') :'checked' }}>
                                <label class="form-check-label" for="best-selling-product-temporary-close-remove">
                                    {{ translate('Remove Product From The List If Store Is Temporarily Off') }}
                                </label>
                            </div>

                            <div class="form-check d-flex gap-1">
                                <input type="radio" class="form-check-input radio--input show"
                                       name="best_selling_product_list_priority[temporary_close_sorting]" value="default"
                                       data-parent-class="best-selling-product"
                                       id="best-selling-product-temporary-close-default"
                                    {{ isset($bestSellingProductListPriority?->temporary_close_sorting) ?($bestSellingProductListPriority?->temporary_close_sorting == 'default' ? 'checked' : '' ) : 'checked' }}>
                                <label class="form-check-label" for="best-selling-product-temporary-close-default">
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
