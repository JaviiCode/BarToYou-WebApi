<?php

namespace App\Http\Controllers;

use App\Http\Resources\orderStatusCollection;
use App\Models\orderStatus;
use App\Http\Requests\StoreorderStatusRequest;
use App\Http\Requests\UpdateorderStatusRequest;

class OrderStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderStatus = orderStatus::paginate(10);
        return new orderStatusCollection($orderStatus);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreorderStatusRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(orderStatus $orderStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(orderStatus $orderStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateorderStatusRequest $request, orderStatus $orderStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(orderStatus $orderStatus)
    {
        //
    }
}
