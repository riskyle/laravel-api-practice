<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\InvoiceCollection;
use App\Models\Invoice;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\InvoiceResource;
use App\Filters\V1\InvoicesFilter;
use App\Http\Requests\BulkStoreInvoiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class InvoiceController extends Controller
{

    public function index(Invoice $invoice, Request $request)
    {
        $filter = new InvoicesFilter();

        $filterItems = $filter->transform($request);

        if (count($filterItems) == 0) {
            return new InvoiceCollection($invoice->paginate());
        } else {
            $invoices = $invoice->where($filterItems)->paginate();
            return new InvoiceCollection($invoices->appends($request->query()));
        }
    }

    public function create()
    {
        //
    }

    public function bulkStore(BulkStoreInvoiceRequest $request)
    {
        $bulk = collect($request->all())->map(function ($arr, $key) {
            //this the array without the customerId, billedDate, and paidDate
            return Arr::except($arr, ['customerId', 'billedDate', 'paidDate']);
        });
        return Invoice::insert($bulk->toArray());
    }

    public function store(StoreInvoiceRequest $request)
    {
        return new InvoiceResource(Invoice::create($request->all()));
    }

    public function show(Invoice $invoice)
    {
        return new InvoiceResource($invoice);
    }

    public function edit(Invoice $invoice)
    {
        //
    }


    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        //
    }

    public function destroy(Invoice $invoice)
    {
        //
    }
}
