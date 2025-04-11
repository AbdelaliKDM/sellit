@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>{{ __('app.customer_details') }}</h2>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('app.back') }} {{ __('app.to_customers') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    @if($customer->image)
                    <div class="mb-3" style="width: 200px; height: 200px; overflow: hidden; border-radius: 50%; margin: 0 auto;">
                        <img src="{{ asset('storage/' . $customer->image) }}" alt="{{ $customer->name }}" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                    </div>
                    @else
                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 200px; height: 200px; font-size: 72px;">
                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                    </div>
                    @endif
                    <h3>{{ $customer->name }}</h3>
                    <div class="mt-3">
                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> {{ __('app.edit') }}
                        </a>
                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('app.confirm_delete_customer') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> {{ __('app.delete') }}
                            </button>
                        </form>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> {{ __('app.contact_information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3 fw-bold">{{ __('app.phone') }}:</div>
                                <div class="col-md-9">
                                    @if($customer->phone)
                                    <a href="tel:{{ $customer->phone }}">{{ $customer->phone }}</a>
                                    @else
                                    <span class="text-muted">{{ __('app.not_provided') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 fw-bold">{{ __('app.address') }}:</div>
                                <div class="col-md-9">
                                    @if($customer->address)
                                    {{ $customer->address }}
                                    @else
                                    <span class="text-muted">{{ __('app.not_provided') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-history"></i> {{ __('app.customer_history') }}</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">{{ __('app.customer_since') }}: {{ $customer->created_at->format('F d, Y') }}</p>
                            <p class="text-muted">{{ __('app.last_updated') }}: {{ $customer->updated_at->format('F d, Y') }}</p>

                            <!-- You can add order history or other customer-related information here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
