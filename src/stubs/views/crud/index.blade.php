@extends('tabler::layout')
@section('content')
    <div class="page-header">
        <div class="row align-items-center">
            @sectionMissing('page_title')
                <x-tabler::page-title :title="trans('permissions.'.$routeName)" :pretitle="trans('admin.title')"/>
                @else
                    @yield('page_title')
                @endif
                <div class="col-auto ms-auto">
                    <div class="btn-list">
                        @sectionMissing('create_button')
                            @hasRoute('admin.'.$routePrefix.'.create')
                            <a href="{{route('admin.'.$routePrefix.'.create',request()->route()->parameters)}}" class="btn btn-primary">@lang('admin.create')</a>
                        @endif
                        @else
                            @yield('create_button')
                        @endif

                        @sectionMissing('search')
                            @if(view()->exists($module.'::admin.'.$routePrefixSingular.'.search-form'))
                                <a href="#" class="btn btn-white" data-bs-toggle="modal" data-bs-target="#modal-search">@lang('admin.search')</a>
                                <x-tabler::modal name="search" :title="trans('admin.search')" :submit-text="trans('admin.search')" form-action="{{request()->url()}}" form-method="GET">
                                    @include($module.'::admin.'.$routePrefixSingular.'.search-form')
                                </x-tabler::modal>
                            @endif
                            @else
                                @yield('search')
                            @endif

                            @sectionMissing('destroy_button')
                                @hasRoute('admin.'.$routePrefix.'.destroy')
                                <a href="#" class="btn btn-danger as-form"
                                   data-action="{{route('admin.'.$routePrefix.'.destroy',[1] + request()->route()->parameters)}}"
                                   data-method="DELETE"
                                   data-form=".table-checkbox">@lang('admin.destroy')</a>
                            @endif
                            @else
                                @yield('destroy_button')
                            @endif
                            @stack('header_button')
                    </div>
                </div>
        </div>
    </div>
    <div class="col-12">
        @yield('table')
    </div>
@endsection
