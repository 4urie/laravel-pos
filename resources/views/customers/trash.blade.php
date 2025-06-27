customers\trash.blade.php
@extends('dashboard.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            @if (session()->has('success'))
                <div class="alert text-white bg-success" role="alert">
                    <div class="iq-alert-text">{{ session('success') }}</div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="ri-close-line"></i>
                    </button>
                </div>
            @endif
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-3">Deleted Customers (Recycle Bin)</h4>
                    <p class="mb-0">This is the list of customers who have been deleted. <br>
                        You can restore them or permanently delete them. </p>
                </div>
                <div>
                    <a href="{{ route('customers.index') }}" class="btn btn-primary add-list"><i class="fa-solid fa-arrow-left mr-3"></i>Back to Customers</a>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3">
                <table class="table mb-0">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>No.</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Shop Name</th>
                            <th>Deleted At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @forelse ($customers as $customer)
                        <tr>
                            <td>{{ (($customers->currentPage() * 10) - 10) + $loop->iteration }}</td>
                            <td>
                                <img class="avatar-60 rounded" src="{{ $customer->photo ? asset('storage/customers/'.$customer->photo) : asset('assets/images/user/1.png') }}">
                            </td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->shopname }}</td>
                            <td>{{ $customer->deleted_at->format('d M Y, h:i A') }}</td>
                            <td>
                                <div class="d-flex align-items-center list-action">
                                    <form action="{{ route('customers.restore', $customer->id) }}" method="POST" style="margin-right:5px">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fa-solid fa-trash-restore mr-0"></i> Restore</button>
                                    </form>
                                    
                                    <form action="{{ route('customers.forceDelete', $customer->id) }}" method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to permanently delete this customer? This action cannot be undone.')" data-toggle="tooltip" data-placement="top" title="Permanently Delete"><i class="fa-solid fa-trash mr-0"></i> Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No deleted customers found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $customers->links() }}
        </div>
    </div>
    <!-- Page end  -->
</div>

@endsection