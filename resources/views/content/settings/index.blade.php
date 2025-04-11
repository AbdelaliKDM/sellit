@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>{{ __('app.system_settings') }}</h2>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('app.close') }}"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <h4>{{ __('app.general_settings') }}</h4>
                    <hr>
                </div>

                <!-- App Name Setting -->
                <div class="mb-3">
                    <label for="app_name" class="form-label">{{ __('app.app_name') }}</label>
                    <input type="text" name="app_name" id="app_name" class="form-control @error('app_name') is-invalid @enderror" value="{{ $settings['app_name'] }}">
                    @error('app_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Language Setting -->
                <div class="mb-3">
                    <label for="language" class="form-label">{{ __('app.default_language') }}</label>
                    <select name="language" id="language" class="form-select @error('language') is-invalid @enderror">
                        <option value="en" {{ $settings['language'] == 'en' ? 'selected' : '' }}>{{ __('app.english') }}</option>
                        <option value="ar" {{ $settings['language'] == 'ar' ? 'selected' : '' }}>{{ __('app.arabic') }}</option>
                        <option value="fr" {{ $settings['language'] == 'fr' ? 'selected' : '' }}>{{ __('app.french') }}</option>
                    </select>
                    @error('language')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Currency Setting -->
                <div class="mb-3">
                    <label for="currency" class="form-label">{{ __('app.default_currency') }}</label>
                    <select name="currency" id="currency" class="form-select @error('currency') is-invalid @enderror">
                        <option value="dollar" {{ $settings['currency'] == 'dollar' ? 'selected' : '' }}>{{ __('app.us_dollar') }} ($)</option>
                        <option value="euro" {{ $settings['currency'] == 'euro' ? 'selected' : '' }}>{{ __('app.euro') }} (€)</option>
                        <option value="dzd" {{ $settings['currency'] == 'dzd' ? 'selected' : '' }}>{{ __('app.algerian_dinar') }} (DZD)</option>
                    </select>
                    @error('currency')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <h4>{{ __('app.user_settings') }}</h4>
                    <hr>
                </div>

                <!-- Registration Setting -->
                <div class="mb-3">
                    <label for="enable_registration" class="form-label">{{ __('app.user_registration') }}</label>
                    <select name="enable_registration" id="enable_registration" class="form-select @error('enable_registration') is-invalid @enderror">
                        <option value="true" {{ $settings['enable_registration'] == 'true' ? 'selected' : '' }}>{{ __('app.enabled') }}</option>
                        <option value="false" {{ $settings['enable_registration'] == 'false' ? 'selected' : '' }}>{{ __('app.disabled') }}</option>
                    </select>
                    @error('enable_registration')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">{{ __('app.registration_help_text') }}</div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> {{ __('app.save_settings') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
