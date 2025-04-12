@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2>{{ __('app.customers') }}</h2>
            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('app.add_new_customer') }}
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('app.close') }}"></button>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('customers.index') }}" method="GET" class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="{{ __('app.search_by_name_phone_address') }}" name="search" value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-2">
                    @if(request('search'))
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> {{ __('app.clear_search') }}
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ __('app.image') }}</th>
                            <th>{{ __('app.name') }}</th>
                            <th>{{ __('app.phone') }}</th>
                            <th>{{ __('app.address') }}</th>
                            <th>{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>
                                @if($customer->image)
                                <div style="width: 50px; height: 50px; overflow: hidden; border-radius: 50%;">
                                    <img src="{{ asset('storage/' . $customer->image) }}" alt="{{ $customer->name }}" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                                </div>
                                @else
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                @endif
                            </td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->phone ?? __('app.not_provided') }}</td>
                            <td>{{ Str::limit($customer->address, 30) ?? __('app.not_provided') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger delete-customer-btn" data-customer-id="{{ $customer->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('app.no_customers_found') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $customers->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Customer Confirmation Modal -->
<div class="modal fade" id="deleteCustomerModal" tabindex="-1" aria-labelledby="deleteCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header justify-content-between">
                <h5 class="modal-title" id="deleteCustomerModalLabel">{{ __('app.confirm_delete_customer') }}</h5>
                <button type="button" class="btn-close ms-0 me-n2" data-bs-dismiss="modal" aria-label="{{ __('app.close') }}"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('app.confirm_delete_customer_message') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.no') }}</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">{{ __('app.yes') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden form for delete submission -->
<form id="delete-customer-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let customerToDelete = null;

        // Show delete confirmation modal
        $('.delete-customer-btn').on('click', function(e) {
            e.preventDefault();
            customerToDelete = $(this).data('customer-id');
            $('#deleteCustomerModal').modal('show');
        });

        // Handle delete confirmation
        $('#confirm-delete-btn').on('click', function() {
            if (customerToDelete) {
                const form = $('#delete-customer-form');
                form.attr('action', `/customers/${customerToDelete}`);
                form.submit();
            }
            $('#deleteCustomerModal').modal('hide');
        });
    });
</script>
@endsection
