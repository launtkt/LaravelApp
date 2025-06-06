@extends('layouts.admin')
@section('content')
<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Tổng quan</h3>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
      </ol>
    </nav>
  </div>

  <div class="row g-3">

    <div class="col-md-3">
      <div class="bg-info text-white rounded p-3">
        <h4 class="fw-bold">150</h4>
        <p>Khách hàng mới</p>
        <a href="#" class="text-white text-decoration-none d-flex justify-content-between align-items-center">
          More info <i class="bi bi-arrow-right-circle-fill"></i>
        </a>
      </div>
    </div>

    <div class="col-md-3">
      <div class="bg-success text-white rounded p-3">
        <h4 class="fw-bold">5</h4>
        <p>Đơn hàng mới</p>
        <a href="#" class="text-white text-decoration-none d-flex justify-content-between align-items-center">
          More info <i class="bi bi-arrow-right-circle-fill"></i>
        </a>
      </div>
    </div>

    <div class="col-md-3">
      <div class="bg-warning text-dark rounded p-3">
        <h4 class="fw-bold">44</h4>
        <p>Doanh thu tháng này</p>
        <a href="#" class="text-dark text-decoration-none d-flex justify-content-between align-items-center">
          More info <i class="bi bi-arrow-right-circle-fill"></i>
        </a>
      </div>
    </div>

    <div class="col-md-3">
      <div class="bg-danger text-white rounded p-3">
        <h4 class="fw-bold">65</h4>
        <p>Sản phẩm sắp hết hàng</p>
        <a href="#" class="text-white text-decoration-none d-flex justify-content-between align-items-center">
          More info <i class="bi bi-arrow-right-circle-fill"></i>
        </a>
      </div>
    </div>

  </div>
</div>
@endsection
