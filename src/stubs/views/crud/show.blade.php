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
                        @hasRoute('admin.'.$routePrefix.'.destroy')
                        <form method="post" action="{{route('admin.'.$routePrefix.'.destroy',[1] + request()->route()->parameters)}}">
                            @csrf
                            @method('delete')
                            <input type="hidden" name="id[]" value="{{$item->id}}">
                            <button class="btn btn-danger" type="submit">@lang('admin.destroy')</button>
                        </form>
                        @endif
                        @hasRoute('admin.'.$routePrefix.'.edit')
                        <a href="{{route('admin.'.$routePrefix.'.edit',request()->route()->parameters)}}" class="btn btn-warning">@lang('admin.edit')</a>
                        @endif
                        @stack('header_button')
                    </div>
                </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter table-mobile-md card-table table-hover">
                    <tbody>
                    @yield('table-rows')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @yield('extra')
@endsection
