@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    {{-- CARD: Thông tin sản phẩm --}}
    <div class="card-pink bg-soft-pink shadow-sm mb-4">
        <div class="card-header p-2">Thông tin sản phẩm</div>
        <div class="card-body bg-white">
            <div class="row">
                <!-- Ảnh chính và phụ -->
                <div class="col-md-5 background-light p-3">
                    @php
                        $mainImage = $product->images->where('is_main', true)->first();
                    @endphp
                    <div class="text-center mb-3">
                        <img src="{{ asset($mainImage?->path ?? 'images/no-image.png') }}" class="img-fluid rounded"
                            style="max-height: 300px">
                    </div>
                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                        @foreach ($product->images as $img)
                            <img src="{{ asset($img->path) }}" class="rounded border"
                                style="height: 60px; width: 60px; object-fit: cover">
                        @endforeach
                    </div>
                </div>

                <!-- Thông tin -->
                <div class="col-md-7 d-flex flex-column justify-content-center">
                    <h5>{{ $product->name }}</h5>
                    <p class="text-muted mb-1">Mã sản phẩm: {{ $product->product_code }}</p>
                    <p><strong>Giá:</strong> {{ number_format($product->price, 0, ',', '.') }}₫</p>
                    <p><strong>Màu sắc:</strong> {{ $product->color ?? 'Không có' }}</p>
                    <p><strong>Kích cỡ:</strong>
                        {{ $product->sizes ? implode(', ', $product->sizes) : 'Không có' }}
                    </p>
                    <p><strong>Mô tả:</strong> {{ $product->description ?? 'Không có' }}</p>

                    <div class="mt-3">
                        <strong>Đánh giá trung bình: {{ $averageStars }}</strong>
                        <span class="text-warning">
                            @for ($i = 1; $i <= 5; $i++)
                                {!! $i <= $averageStars ? '★' : '☆' !!}
                            @endfor
                        </span>
                        ({{ $totalReviews }} đánh giá)
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CARD: Tabs đánh giá và bình luận --}}
    <div class="card shadow-sm border-pink">
        <div class="card-header bg-light">
            <ul class="nav nav-tabs card-header-tabs" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews"
                        type="button" role="tab">Đánh giá</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="comments-tab" data-bs-toggle="tab" data-bs-target="#comments"
                        type="button" role="tab">Bình luận</button>
                </li>
            </ul>
        </div>

        <div class="card-body bg-white tab-content" id="productTabsContent">
            {{-- Tab: Đánh giá --}}
            <div class="tab-pane fade show active" id="reviews" role="tabpanel">
                @forelse($reviews as $review)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>{{ $review->user->username ?? 'Ẩn danh' }}</strong>
                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="text-warning mb-2">
                            @for ($i = 1; $i <= 5; $i++)
                                {!! $i <= $review->stars ? '★' : '☆' !!}
                            @endfor
                        </div>
                        <p class="mb-1">{{ $review->review }}</p>
                        @if ($review->image_path)
                            <img src="{{ asset($review->image_path) }}" class="img-thumbnail"
                                style="max-height: 150px">
                        @endif
                        @if ($review->reply)
                            <div class="seller-reply mt-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <strong class="text-primary">Phản hồi của Người Bán</strong>
                                </div>
                                <p class="mb-0">{!! nl2br(e($review->reply)) !!}</p>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này.</p>
                @endforelse
                {{ $reviews->links() }}
            </div>

            {{-- Tab: Bình luận --}}
            <div class="tab-pane fade" id="comments" role="tabpanel">
                @forelse($comments as $comment)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>{{ $comment->user->username ?? 'Ẩn danh' }}</strong>
                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1">{{ $comment->content }}</p>
                        @if ($comment->reply)
                            <div class="seller-reply mt-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <strong class="text-primary">Phản hồi của Người Bán</strong>
                                </div>
                                <p class="mb-0">{!! nl2br(e($comment->reply)) !!}</p>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-muted">Chưa có bình luận nào cho sản phẩm này.</p>
                @endforelse
                {{ $comments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
