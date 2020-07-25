@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Status Order') }}</div>

                    <div class="card-body">
                        @error('proccess_error')
                        <div class="alert alert-danger" role="alert">
                            {{ $message }}
                        </div>
                        @enderror

                        <table class="table table-condensed">
                            <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Customer Email</th>
                                <th>Customer Mobile</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{$order->customer_name}}</td>
                                <td>{{$order->customer_email}}</td>
                                <td>{{$order->customer_mobile}}</td>
                                @if($order->is_created || $order->is_rejected)
                                    <td>{{$order->status}}
                                        <a href="{{ route('orders.update', [$order->id]) }}" class="btn btn-success"
                                           onclick="event.preventDefault(); document.getElementById('send-form').submit();">{{ __('Go To Pay') }}</a>
                                        <form id="send-form" action="{{ route('orders.update', [$order->id]) }}" method="POST"
                                              style="display: none;">
                                            @csrf
                                            @method('PUT')
                                        </form>
                                    </td>
                                @else
                                    <td>{{$order->status}}</td>
                                @endif
                                <td>{{$order->created_at->diffForHumans()}}</td>
                                <td>{{$order->updated_at->diffForHumans()}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

