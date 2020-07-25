<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Order;
use Dnetix\Redirection\PlacetoPay;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class OrdersController extends Controller
{
    private $placetoPay;

    public function __construct(PlacetoPay $placetoPay)
    {
        $this->placetoPay = $placetoPay;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|Response|View
     */
    public function index()
    {
        $orders = Order::get();
        return view('orders.index')->with('orders', $orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|Response|View
     */
    public function create()
    {
        return view('orders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreOrderRequest $request
     * @return RedirectResponse
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            $order = Order::create($request->validated());
            return redirect()->to(route('orders.edit', [$order->id]));
        } catch (Exception $exception) {
            logger('Orders.store', [
                $exception->getCode(),
                $exception->getMessage()
            ]);
            return redirect()->to(route('orders.create'))
                ->withInput($request->input())
                ->withErrors(['proccess_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Order $order
     * @return Application|Factory|RedirectResponse|View
     */
    public function show(Order $order)
    {
        try {
            $transaccion = session("transacction_{$order->id}");
            $response = $this->placetoPay->query($transaccion);
            if ($response->isSuccessful()) {

                if ($response->status()->isApproved()) {
                    $order->status = Order::STATUS_PAYED;
                }
                if ($response->status()->isRejected()) {
                    $order->status = Order::STATUS_REJECTED;
                }

                $order->save();
                return view('orders.show')->with('order', $order->refresh());
            } else {
                throw new Exception($response->status()->message());
            }
        } catch (Exception $exception) {
            return redirect()->to(route('orders.edit', [$order->id]))
                ->withErrors(['proccess_error' => $exception->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Order $order
     * @return Application|Factory|View
     */
    public function edit(Order $order)
    {
        return view('orders.show')->with('order', $order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Order $order
     * @return RedirectResponse|void
     */
    public function update(Request $request, Order $order)
    {
        try {
            $request = $this->makeRequestPlaceToPay($order);
            $response = $this->placetoPay->request($request);
            if ($response->isSuccessful()) {
                session()->put("transacction_{$order->id}", $response->requestId());
                return redirect()->to($response->processUrl());
            } else {
                throw new Exception($response->status()->message());
            }
        } catch (Exception $exception) {
            logger('Orders.update', [
                $exception->getCode(),
                $exception->getMessage()
            ]);
            return redirect()->to(route('orders.edit', [$order->id]))
                ->withErrors(['proccess_error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Order $order
     * @return void
     */
    public function destroy(Order $order)
    {
        //
    }

    private function makeRequestPlaceToPay(Order $order)
    {
        
        return [
            'payment' => [
                'reference' => $order->id,
                'description' => 'Testing payment ' . $order->id,
                'amount' => [
                    'currency' => 'COP',
                    'total' => random_int(10000, 100000),
                ],
            ],
            'expiration' => date('c', strtotime('+2 days')),
            'returnUrl' => route('orders.show', [$order->id]),
            'ipAddress' => '127.0.0.1',
            'userAgent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36',
        ];
    }
}
