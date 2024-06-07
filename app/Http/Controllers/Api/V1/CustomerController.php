<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\CustomerResource;
use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CustomerCollection;
use Illuminate\Http\Request;
use App\Filters\V1\CustomersFilter;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{

    public function index(Customer $customer, Request $request)
    {
        $filter = new CustomersFilter();



        $filterItems = $filter->transform($request); // [['column', 'operator', 'value']]

        $includeInvoices = $request->query('includeInvoices');

        $customers = $customer->where($filterItems);

        if ($includeInvoices) {
            $customers = $customers->with(['invoice']);
        }

        return new CustomerCollection($customers->paginate()->appends($request->query()));
    }

    public function store(StoreCustomerRequest $request)
    {
        return new CustomerResource(Customer::create($request->all()));
    }

    public function show(Customer $customer)
    {
        $includeInvoices = request()->query('includeInvoices');

        if ($includeInvoices) {
            return new CustomerResource($customer->loadMissing('invoice'));
        }
        return new CustomerResource($customer);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->all());
    }

    public function destroy(Customer $customer)
    {

        if (!auth()->user()->tokenCan('delete')) {

            return response()->json(["message" => "You are not allowed to delete data!"]);
        }

        $customer->delete();

        return response()->json(["message" => "Succesfully Deleted"]);
    }
}
