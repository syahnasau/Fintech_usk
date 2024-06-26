@extends('layouts.app')

@php
    function rupiah($angka)
    {
        $hasil_rupiah = 'Rp' . number_format($angka, 0, ',', '.');
        return $hasil_rupiah;
    }
@endphp


@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                @include('components.alert')

                @if (Auth::user()->role == 'siswa')
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row">

                                <div class="col">
                                    <div class="">
                                        <p class="">Balance : </p>
                                        <h4 class="card-text"> {{ rupiah($saldo) }}</h4>
                                    </div>
                                </div>
                                <div class="col text-end">
                                    <button type="button" class="btn btn-success px-5" data-bs-target="#formTransfer" data-bs-toggle="modal">Withdraw</button>
                                    <button type="button" class="btn btn-success px-5" data-bs-target="#formTopUp" data-bs-toggle="modal">Top Up</button>

                                    <!-- Modal -->
                                    <form action="{{ route('topUpNow') }}" method="post">
                                        @csrf

                                        <div class="modal fade" id="formTopUp" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Enter the Top Up
                                                            nominal</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <input type="number" name="credit" id=""
                                                                class="form-control" min="10000" value="10000">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Top Up Now</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <!-- Modal Tarik Tunai -->
                                    <form action="{{ route('withdrawNow') }}" method="post">
                                        @csrf

                                        <div class="modal fade" id="formTransfer" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Withdraw</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <input type="number" name="debit" id=""
                                                                class="form-control" min="10000" value="10000">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Withdraw Now</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- Modal Tarik Tunai -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row start -->
                    <div class="row">
                        <div class="col-sm-12 col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Product List</div>
                                </div>
                                <div class="card-body">

                                    <!-- Row start -->
                                    <div class="row">
                                        @foreach ($products as $product)
                                            <div class="col-md-4 col-lg-4 col-sm-6 col-12 ">
                                                <form action="{{ route('addToCart') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="price" value="{{ $product->price }}">

                                                    <div class="product-card shadow-sm">

                                                        <img class="product-card-img-top img-cover" src="{{ $product->photo }}" alt="Bootstrap Gallery" height="120" style="object-fit: cover;">
                                                        <div class="product-card-body">
                                                            <h5 class="product-title">{{ $product->name }}</h5>
                                                            <div class="product-price">
                                                                <div class="actucal">{{ rupiah($product->price) }}</div>

                                                            </div>
                                                            <div class="product-description">
                                                                <div class="off-price">Stock: {{ $product->stock }}</div>
                                                                {{ $product->description }}
                                                            </div>
                                                            <div class="product-actions">
                                                                <div class="row">
                                                                    <div class="col d-flex justify-content-start">
                                                                        <div>
                                                                            <input class="form-control" type="number"name="quantity" value="1" min="1" id="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col d-flex justify-content-end">
                                                                        <button type="submit" class="btn btn-outline-primary" {{ $product->stock <= 0 ? 'disabled' : '' }}> + </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        @endforeach

                                    </div>
                                    <!-- Row end -->

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Chart List</div>
                                </div>
                                <div class="card-body">
                                    @foreach ($carts as $key => $cart)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $cart->product->name }} |
                                            {{ $cart->quantity }}
                                            <span class="">{{ rupiah($cart->price * $cart->quantity) }}</span>
                                            <form action="{{ route('transaction.destroy', ['id' => $cart->id]) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger bi bi-x m-1"></button>
                                            </form>
                                        </li>
                                    @endforeach
                                </div>
                                <div class="card-footer">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <span class="">Total Amount :</span>
                                            <h4 class="">{{ rupiah($total_biaya) }}</h4>
                                        </div>
                                        <div class="col text-end">
                                            <form action="{{ route('payNow') }}" method="post">
                                                @csrf
                                                <button type="submit" class="btn btn-primary py-auto">Pay Now</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row pb-2">
                                <div class="col">
                                    <div class="card bg-white shadow-sm border-0">
                                        <div class="card-header border-0">
                                            History Transaction
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group border-0">
                                                @foreach ($transactions as $key => $transaction)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div class="row">
                                                            <ul>
                                                                <li class="col">{{ $transaction[0]->order_id }}</li>
                                                            </ul>
                                                        </div>
                                                        <a href="{{ route('download', $transaction[0]->order_id) }}" class="bi bi-filetype-pdf bi-lg btn btn-outline-primary" target="_blank"></a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="">

                                    <div class="card bg-white shadow-sm border-0">
                                        <div class="card-header border-0">
                                            Mutasi Transaction
                                        </div>

                                        <div class="card-body">
                                            <ul class="list-group">
                                                @foreach ($mutasi as $data)
                                                    <li class="list-group-item">
                                                        <div class="d-flex  justify-content-between align-items-center">
                                                            <div>
                                                                @if ($data->credit)
                                                                    <span class="text-success fw-bold">Credit : </span>Rp
                                                                    {{ rupiah($data->credit) }}
                                                                @else
                                                                    <span class="text-danger fw-bold">Debit : </span>Rp
                                                                    {{ rupiah($data->debit) }}
                                                                @endif
                                                            </div>
                                                            <div class="">
                                                                <span class="badge rounded-pill border border-warning text-warning">{{$data->status == 'process' ? 'PROSES' : ''}}</span>
                                                                @if ($data->status == 'process')

                                                                @endif
                                                            </div>
                                                        </div>
                                                        {{ $data->description }}
                                                        <p class="text-grey">Date : {{ $data->created_at }}</p>

                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                @endif

                @if (Auth::user()->role == 'bank')
                    <div class="">

                        <div class="row">
                            <div class="col-xxl-4 col-sm-6 col-12">
                                <div class="stats-tile">
                                    <div class="sale-icon shade-green">
                                        <i class="bi bi-handbag"></i>
                                    </div>
                                    <div class="sale-details">
                                        <h3 class="text-green"> {{ $saldo }}</h3>
                                        <p>Balance</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-sm-6 col-12">
                                <div class="stats-tile">
                                    <div class="sale-icon shade-red">
                                        <i class="bi bi-pie-chart"></i>
                                    </div>
                                    <div class="sale-details">
                                        <h3 class="text-red">{{ $allMutasi }}</h3>
                                        <p>Transaction</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-sm-6 col-12">
                                <div class="stats-tile">
                                    <div class="sale-icon shade-blue">
                                        <i class="bi bi-emoji-smile"></i>
                                    </div>
                                    <div class="sale-details">
                                        <h3 class="text-blue">{{ $nasabah }}</h3>
                                        <p>Customers</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-xxl-7  col-sm-12 col-12">

                                <div class="card bg-white shadow-sm border-0 mb-4">
                                    <div class="card-header border-0">
                                        Request Top Up Customer
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">

                                                @foreach ($request_payment as $request)
                                                    <form action="{{ route('acceptRequest') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="wallet_id" value="{{ $request->id }}">
                                                        <div class="card bg-white shadow-sm border-0 mb-3">
                                                            <div class="card-header border-0">
                                                                {{ $request->user->name }}
                                                            </div>
                                                            <div class="card-body d-flex justify-content-between">

                                                                <div class="col my-auto">
                                                                    @if ($request->credit)
                                                                        <span class="text-green fw-bold">Top Up :</span> {{ rupiah($request->credit) }}
                                                                    @elseif ($request->debit)
                                                                    <span class="text-red fw-bold">Withdraw :</span> {{ rupiah($request->debit) }}
                                                                    @endif
                                                                    <div class="text-secondary">
                                                                        <p>{{ $request->created_at }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col text-end">
                                                                    <button type="submit" class="btn btn-primary">Accept Request</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                @endforeach


                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-xxl-5  col-sm-12">
                                <div class="card bg-white shadow-sm border-0">
                                    <div class="card-header border-0">
                                        History Transaction
                                    </div>

                                    <div class="card-body">
                                        <ul class="list-group">
                                            @foreach ($mutasi as $data)
                                                <li class="list-group-item">
                                                    <div class="d-flex  justify-content-between align-items-center">
                                                        <div>
                                                            @if ($data->credit)
                                                                <span class="text-success fw-bold">Credit : </span>Rp
                                                                {{ rupiah($data->credit) }}
                                                            @else
                                                                <span class="text-danger fw-bold">Debit : </span>Rp
                                                                {{ rupiah($data->debit) }}
                                                            @endif

                                                        </div>

                                                    </div>
                                                    Name : {{ $data->user->name }}
                                                    <p class="text-grey">{{ $data->description }}</p>
                                                    <p class="text-grey">Date : {{ $data->created_at }}</p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Row end -->
                    </div>
                @endif

                @if (Auth::user()->role == 'kantin')


                    <div class="row">

                        <div class="col-xxl-4 col-sm-12 ">
                            <div class="stats-tile">
                                <div class="sale-icon shade-green">
                                    <i class="bi bi-handbag"></i>
                                </div>
                                <div class="sale-details">
                                    <h3 class="text-green">{{ $saldo }}</h3>
                                    <p>Balance</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-sm-6 col-12">
                            <div class="stats-tile">
                                <div class="sale-icon shade-red">
                                    <i class="bi bi-pie-chart"></i>
                                </div>
                                <div class="sale-details">
                                    <h3 class="text-red">{{ $allProducts }}</h3>
                                    <p>Product</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-sm-6 col-12">
                            <div class="stats-tile">
                                <div class="sale-icon shade-red">
                                    <i class="bi bi-pie-chart"></i>
                                </div>
                                <div class="sale-details">
                                    <h3 class="text-red">{{ $transactions }}</h3>
                                    <p>Transaction</p>
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="row">
                            <div class="col-xxl-8 col-lg-8 col-sm-12 col-12">

                                <div class="row">
                                    <div class="col">

                                        <div class="card">
                                            <div class="card-header  d-flex justify-content-between align-items-center">
                                                <div class="card-title">
                                                    <div class="ms-2">Product List</div>
                                                </div>
                                                <a href="{{ route('product.create') }}" class="btn btn-primary ms-auto">
                                                    <i class="bi bi-plus"></i> Add
                                                </a>
                                            </div>

                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table v-middle">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Name</th>
                                                                <th>Price</th>
                                                                <th>Photo</th>
                                                                <th>Stock</th>
                                                                <th>Desc</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($products as $key => $product)
                                                                <tr>
                                                                    <td>{{ $key + 1 }}</td>
                                                                    <td>{{ $product->name }}</td>
                                                                    <td>{{ $product->price }}</td>
                                                                    <td><img src="{{ $product->photo }}" width="50" height="50" style="object-fit: cover;"></td>
                                                                    <td>{{ $product->stock }}</td>
                                                                    <td>{{ $product->description }}</td>

                                                                    <td class="p-auto d-flex justify-content-roundly " >
                                                                        <a href="{{ route('product.edit', $product) }}" class="btn btn-warning bi bi-pencil m-1"></a>
                                                                        <form action="{{ route('product.destroy', $product->id) }}" method="post">
                                                                            @csrf
                                                                            @method('delete')
                                                                            <button type="submit" class="btn btn-danger bi bi-trash m-1" onclick="return confirm('Apakah anda yakin hapus {{ $product->name }}')"></button>
                                                                        </form>

                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">

                                    <div class="card bg-white shadow-sm border-0">
                                        <div class="card-header border-0">History Transaction</div>
                                        <div class="card-body">
                                            <ul class="list-group border-0">
                                                @foreach ($transactionAll as $key => $transaction)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div class="row">
                                                            <ul>
                                                                <li class="col">{{ $transaction[0]->user->name }}</li>
                                                                <li class="col">{{ $transaction[0]->order_id }}</li>
                                                                <li class="col"><p>{{ $transaction[0]->created_at }}</p> </li>
                                                            </ul>
                                                        </div>

                                                        <a href="{{ route('download', $transaction[0]->order_id) }}" class="bi bi-filetype-pdf bi-lg btn btn-outline-primary" target="_blank"></a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                            </div>



                            <!-- Row end -->
                        </div>
                @endif

                @if (Auth::user()->role == 'admin')

                    <div class="row">
                        <div class="col-xxl-4 col-sm-6 col-12">
                            <div class="stats-tile">
                                <div class="sale-icon shade-green">
                                    <i class="bi bi-handbag"></i>
                                </div>
                                <div class="sale-details">
                                    <h3 class="text-green"> {{ $products }}</h3>
                                    <p>Products</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-sm-6 col-12">
                            <div class="stats-tile">
                                <div class="sale-icon shade-red">
                                    <i class="bi bi-pie-chart"></i>
                                </div>
                                <div class="sale-details">
                                    <h3 class="text-red">{{ $transactions }}</h3>
                                    <p>Transaction</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-sm-6 col-12">
                            <div class="stats-tile">
                                <div class="sale-icon shade-blue">
                                    <i class="bi bi-emoji-smile"></i>
                                </div>
                                <div class="sale-details">
                                    <h3 class="text-blue">{{ $user }}</h3>
                                    <p>User</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xxl-7 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-header  d-flex justify-content-between align-items-center">
                                    <div class="card-title">
                                        <div class="ms-2">User List</div>
                                    </div>
                                    <a href="{{ route('user.create') }}" class="btn btn-primary ms-auto">
                                        <i class="bi bi-plus"></i> Add
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table v-middle">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Role</th>
                                                    <th>Email</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($userAll as $key => $user)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $user->name }}</td>
                                                        <td>{{ $user->role }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        <td class="p-auto d-flex justify-content-roundly " >
                                                            <a href="{{ route('user.edit', $user) }}" class="btn btn-warning bi bi-pencil m-1"></a>
                                                            <form action="{{ route('user.destroy', $user->id) }}" method="post">
                                                                @csrf
                                                                @method('delete')
                                                                <button type="submit" class="btn btn-danger bi bi-trash m-1" onclick="return confirm('Apakah anda yakin hapus {{ $user->name }}')"></button>
                                                            </form>

                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card bg-white shadow-sm border-0">
                                <div class="card-header border-0">
                                    History Transaction
                                </div>

                                <div class="card-body">
                                    <ul class="list-group">
                                        @foreach ($mutasi as $data)
                                            <li class="list-group-item">
                                                <div class="d-flex  justify-content-between align-items-center">
                                                    <div>
                                                        @if ($data->credit)
                                                            <span class="text-success fw-bold">Credit : </span>Rp
                                                            {{ rupiah($data->credit) }}
                                                        @else
                                                            <span class="text-danger fw-bold">Debit : </span>Rp
                                                            {{ rupiah($data->debit) }}
                                                        @endif

                                                    </div>
                                                    <div class="">
                                                        <span class="badge rounded-pill border border-warning text-warning">{{$data->status == 'process' ? 'PROSES' : ''}}</span>
                                                        @if ($data->status == 'process')

                                                        @endif
                                                    </div>
                                                </div>
                                                Name : {{ $data->user->name }}
                                                <p class="text-grey">Date : {{ $data->created_at }}</p>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            {{-- <div class="card bg-white shadow-sm border-0">
                                <div class="card-header border-0">
                                    History Transaction
                                </div>
                                <div class="card-body">
                                    <ul class="list-group border-0">
                                        @foreach ($transactionsAll as $key => $transaction)
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <div class="row">
                                                    <ul>

                                                        <li class="col">{{ $user->name}}</li>
                                                        <li class="col">{{ $transaction[0]->order_id }}</li>
                                                        <li class="col"><p>{{ $transaction[0]->created_at }}</p> </li>
                                                    </ul>
                                                </div>

                                                <a href=""
                                                    class="bi bi-filetype-pdf bi-lg btn btn-outline-primary" target="_blank"></a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div> --}}
                        </div>
                    </div>



                @endif
            </div>
        </div>
    </div>
@endsection
