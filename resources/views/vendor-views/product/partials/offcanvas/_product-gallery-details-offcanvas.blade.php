
    <div class="offcanvas-sidebar" id="offcanvasProductGalleryDetails{{$product->id}}"
        aria-labelledby="offcanvasProductGalleryDetails{{ $product->id }}Label" style="--bs-offcanvas-width: 560px;">
        <div class="offcanvas-overlay" data-dismiss="offcanvas"></div>

        <div class="offcanvas-content bg-white d-flex flex-column">
            <div class="offcanvas-header bg-light d-flex justify-content-between align-items-center p-3">
                <h3 class="text-capitalize m-0">{{ translate('Product Details') }}</h3>
                <button type="button" class="btn btn-circle bg-white text-dark fs-10" style="--size: 1.5rem;" data-dismiss="offcanvas" aria-label="Close">
                    <i class="fi fi-rr-cross d-flex"></i>
                </button>
            </div>

            <div class="offcanvas-body p-3 overflow-auto flex-grow-1">
                <div class="product-gallery-item mb-4" id="product-gallery-item-{{ $product->id }}">
                    <div class="pd-img-wrap position-relative d-flex gap-4 gap-sm-3 flex-column flex-sm-row">
                        <div class="">
                            <div class="quickviewSlider2 swiper-container border rounded aspect-1 inline-size-100 max-w-130 mx-auto position-relative" id="offcanvasQuickviewSlider2-{{ $product->id }}">
                                <div class="swiper-wrapper">
                                    @php
                                        $imageSources = ($product->product_type === 'physical' && !empty($product->retailDetail?->color_image) && count($product->color_images_full_url) > 0)
                                            ? $product->color_images_full_url
                                            : $product->images_full_url;
                                    @endphp

                                    @foreach ($imageSources as $key => $photo)
                                        @php
                                            $imagePath = isset($photo['image_name'])
                                                ? getStorageImages(path: $photo['image_name'], type: 'backend-product')
                                                : getStorageImages(path: $photo, type: 'backend-product');
                                        @endphp
                                        <div class="swiper-slide position-relative rounded border aspect-1">
                                            <div class="easyzoom easyzoom--overlay is-ready">
                                                <a href="{{ $imagePath }}">
                                                    <img class="h-100 aspect-1 rounded min-w-130" alt="" src="{{ $imagePath }}">
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-3">
                            <h4 class="text-capitalize mb-0">{{ $product['name'] }}</h4>
                            @if ($product?->tags->count() > 0)
                                <div class="d-flex gap-2 align-items-center text-dark">
                                    <div>{{ translate('Tag') }}:</div>

                                    <div class="d-flex flex-wrap gap-2 align-items-center fs-12 collapse-tag-div">

                                        @foreach ($product->tags->take(4) as $tag)
                                            <span class="bg-section2 rounded max-w-70 px-2 py-1 line-1">
                                                {{ $tag->tag }}
                                            </span>
                                        @endforeach

                                        @foreach ($product->tags->skip(4) as $tag)
                                            <span class="bg-section2 rounded max-w-70 px-2 py-1 line-1 extra-tag d-none">
                                                {{ $tag->tag }}
                                            </span>
                                        @endforeach

                                        @if ($product->tags->count() > 4)
                                            <button type="button" class="fw-semibold fs-12 border-0 bg-transparent p-0 collapse-tag-count">
                                                {{ $product->tags->count() - 4 }}+
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="mt-1 user-select-none">
                                <div class="quickviewSliderThumb2 swiper-container position-relative active-border" id="offcanvasQuickviewSliderThumb2-{{ $product->id }}">
                                    <div class="swiper-wrapper auto-item-width side__slider-custom justify-content-start">
                                        @foreach ($imageSources as $key => $photo)
                                            @php
                                                $imagePath = isset($photo['image_name'])
                                                    ? getStorageImages(path: $photo['image_name'], type: 'backend-product')
                                                    : getStorageImages(path: $photo, type: 'backend-product');
                                            @endphp
                                            <div class="swiper-slide position-relative rounded border aspect-1" role="group" style="--size: 40px;">
                                                <img class="aspect-1" alt="" src="{{ $imagePath }}">
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="swiper-button-next" id="offcanvas-swiper-button-next-{{ $product->id }}" style="--size: 20px;"></div>
                                    <div class="swiper-button-prev" id="offcanvas-swiper-button-prev-{{ $product->id }}" style="--size: 20px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-10px bg-section rounded mb-3 overflow-wrap-anywhere">
                    <div class="view--more rich-editor-html-content">
                        <div class="d-flex flex-column gap-1 fs-12">
                            {!! $product['details'] !!}
                        </div>
                    </div>
                    <button type="button" class="expandable-btn d-none">
                        <span class="btn btn-outline--primary btn-sm bg-white text--primary">
                            <span class="more">{{ translate('See More') }}</span>
                            <span class="less d-none">{{ translate('See Less') }}</span>
                        </span>
                    </button>
                </div>
                <div class="p-10px bg-section rounded mb-3 overflow-wrap-anywhere">
                    <h5 class="mb-2">{{ translate('General Information') }}</h5>
                    <table class="fs-12">
                        <tbody>
                        <tr>
                            <td class="py-1 text-nowrap min-w-120">{{ translate('Product Type') }}</td>
                            <td class="py-1 px-2">:</td>
                            <td class="py-1 text-dark fw-medium overflow-wrap-anywhere">
                                {{ translate($product->product_type) }}
                            </td>
                        </tr>
                        @if (!empty($product->category?->default_name))
                            <tr>
                                <td class="py-1 text-nowrap min-w-120">{{ translate('Category') }}</td>
                                <td class="py-1 px-2">:</td>
                                <td class="py-1 text-dark fw-medium overflow-wrap-anywhere">
                                    {{ $product->category->default_name }}
                                </td>
                            </tr>
                        @endif
                        @if (!empty($product->subCategory?->default_name))
                            <tr>
                                <td class="py-1 text-nowrap min-w-120">{{ translate('Subcategory') }}</td>
                                <td class="py-1 px-2">:</td>
                                <td class="py-1 text-dark fw-medium overflow-wrap-anywhere">
                                    {{ $product->subCategory->default_name }}
                                </td>
                            </tr>
                        @endif
                        @if ($product->product_type == 'physical' && !empty($product->brand?->default_name))
                            <tr>
                                <td class="py-1 text-nowrap min-w-120">{{ translate('Brand') }}</td>
                                <td class="py-1 px-2">:</td>
                                <td class="py-1 text-dark fw-medium overflow-wrap-anywhere">
                                    {{ $product->brand->default_name }}
                                </td>
                            </tr>
                        @endif
                        @if ($product->product_type == 'physical')
                            <tr>
                                <td class="py-1 text-nowrap min-w-120">{{ translate('Unit') }}</td>
                                <td class="py-1 px-2">:</td>
                                <td class="py-1 text-dark fw-medium overflow-wrap-anywhere">
                                    {{ $product->unit }}
                                </td>
                            </tr>
                            @if( $product->current_stock > 0)
                            <tr>
                                <td class="py-1 text-nowrap min-w-120">{{ translate('Current Stock') }}</td>
                                <td class="py-1 px-2">:</td>
                                <td class="py-1 text-dark fw-medium overflow-wrap-anywhere">
                                    {{ $product->current_stock }}
                                </td>
                            </tr>
                            @endif
                        @endif
                        <tr>
                            <td class="py-1 text-nowrap min-w-120">{{ translate('Product SKU') }}</td>
                            <td class="py-1 px-2">:</td>
                            <td class="py-1 text-dark fw-medium overflow-wrap-anywhere">
                                {{ $product->code }}
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>

                <div class="p-10px bg-section rounded mb-3 overflow-wrap-anywhere">
                    <h5 class="mb-2">{{ translate('Price Information') }}</h5>
                    <table class="fs-12">
                        <tbody>
                        <tr>
                            <td class="py-1 text-nowrap min-w-120">{{ translate('Unit Price') }}</td>
                            <td class="py-1 px-2">:</td>
                            <td class="py-1 text-dark fw-medium overflow-wrap-anywhere">
                                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $product->unit_price), currencyCode: getCurrencyCode()) }}
                            </td>
                        </tr>
                        @if ($product->product_type == 'physical')
                            <tr>
                                <td class="py-1 text-nowrap min-w-120">{{ translate('Shipping Cost') }}</td>
                                <td class="py-1 px-2">:</td>
                                <td class="py-1 text-dark fw-medium overflow-wrap-anywhere">
                                    {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $product->shipping_cost)) }}
                                    @if ($product->multiply_qty == 1)
                                        ({{ translate('Multiply with quantity') }})
                                    @endif
                                </td>
                            </tr>
                        @endif
                        @if (getProductPriceByType(product: $product, type: 'discount', result: 'value') > 0)
                            <tr>
                                <td class="py-1 text-nowrap min-w-120">{{ translate('Discount') }}</td>
                                <td class="py-1 px-2">:</td>
                                <td class="py-1 text-dark fw-medium overflow-wrap-anywhere">
                                    {{ getProductPriceByType(product: $product, type: 'discount', result: 'string') }}
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                @if (count($galleryVariations[$product['id']]['availableVariations']) > 0)
                    <div class="p-10px bg-section rounded mb-3 overflow-wrap-anywhere">
                        <h5 class="mb-2">{{ translate('Available Variations') }}</h5>
                        <table class="fs-12">
                            <tbody>
                            @foreach ($galleryVariations[$product['id']]['availableVariations'] as $availableVariation)
                                <tr>
                                    <td class="py-1 text-nowrap">{{ $availableVariation['label'] }}</td>
                                    <td class="py-1 px-2">:</td>
                                    <td class="py-1 text-dark fw-medium overflow-wrap-anywhere">
                                        {{ $availableVariation['value'] }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if (count($galleryVariations[$product['id']]['variationRows']) > 0)
                    <div class="p-10px bg-section rounded mb-3 overflow-wrap-anywhere">
                        <h5 class="mb-2">{{ translate('Variation') }}</h5>
                        <div class="table-responsive datatable-custom">
                            <table class="table table-borderless table-align-middle card-table w-100 text-start">
                                <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{ translate('Variation') }}</th>
                                    <th>{{ translate('SKU') }}</th>
                                    <th class="text-center">{{ translate('Variation wise price') }}</th>
                                    <th class="text-center">{{ translate('Stock') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($galleryVariations[$product['id']]['variationRows'] as $variationRow)
                                    <tr>
                                        <td class="text-capitalize text-wrap overflow-wrap-anywhere">{{ $variationRow['variation'] }}</td>
                                        <td class="text-wrap overflow-wrap-anywhere">{{ $variationRow['sku'] }}</td>
                                        <td class="text-center">{{ $variationRow['price'] }}</td>
                                        <td class="text-center">{{ $variationRow['stock'] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if (count($galleryVariations[$product['id']]['digitalVariationRows']) > 0)
                    <div class="p-10px bg-section rounded mb-3 overflow-wrap-anywhere">
                        <h5 class="mb-2">{{ translate('Digital Variation') }}</h5>
                        <div class="table-responsive datatable-custom">
                            <table class="table table-borderless table-align-middle card-table w-100 text-start">
                                <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th class="text-center">{{ translate('Serial') }}</th>
                                    <th class="text-center">{{ translate('Variation Name') }}</th>
                                    <th class="text-center">{{ translate('SKU') }}</th>
                                    <th class="text-center">{{ translate('Price') }}</th>
                                    @if ($galleryVariations[$product['id']]['hasDigitalFileDownload'])
                                        <th class="text-center">{{ translate('Action') }}</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($galleryVariations[$product['id']]['digitalVariationRows'] as $digitalVariationRow)
                                    <tr>
                                        <td class="text-center">{{ $digitalVariationRow['serial'] }}</td>
                                        <td class="text-center text-capitalize">{{ $digitalVariationRow['name'] }}</td>
                                        <td class="text-center">{{ $digitalVariationRow['sku'] }}</td>
                                        <td class="text-center">{{ $digitalVariationRow['price'] }}</td>
                                        @if ($galleryVariations[$product['id']]['hasDigitalFileDownload'])
                                            <td class="text-center">
                                                <span class="btn p-0 getDownloadFileUsingFileUrl" data-toggle="tooltip"
                                                      title="{{ $digitalVariationRow['filePath'] ? translate('Download') : translate('Not found') }}"
                                                      data-file-path="{{ $digitalVariationRow['filePath'] }}" download>
                                                    <img src="{{ asset(path: 'public/assets/back-end/img/icons/download-green.svg') }}"
                                                         alt="{{ $digitalVariationRow['name'] }}">
                                                </span>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>

            <div class="offcanvas-footer offcanvas-footer-sticky shadow-popup d-flex gap-3 bg-white px-3 px-sm-4 py-3">
                <button type="reset" class="btn btn-secondary w-100 px-2 fs-12-mobile" data-dismiss="offcanvas">{{ translate('Cancel') }}</button>
                <a href="{{ route('vendor.retail.products.update', ['id' => $product['id'], 'product-gallery' => 1])}}" class="btn btn--primary w-100 px-2 fs-12-mobile">{{ translate('Use as template') }}</a>
            </div>
        </div>
    </div>
