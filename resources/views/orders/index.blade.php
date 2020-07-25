@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Orders') }} <a class="btn btn-success" href="{{route('orders.create')}}">New</a> </div>
                    
                    <div class="card-body">

                        <table class="table table-condensed">
                            <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Customer Email</th>
                                <th>Customer Mobile</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>{{$order->customer_name}}</td>
                                    <td>{{$order->customer_email}}</td>
                                    <td>{{$order->customer_mobile}}</td>
                                    <td>{{$order->status}}</td>
                                    <td>{{$order->created_at->diffForHumans()}}</td>
                                    <td>{{$order->updated_at->diffForHumans()}}</td>
                                    <td><a class="btn btn-info" href="{{route('orders.edit', [$order->id])}}">Edit</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">{{__('There are no registration orders')}}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


