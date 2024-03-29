@extends('tabler::layout')
@section('content')
    <div class="page-header">
        <x-tabler::page-title :title="trans('permissions.'.$routeName)" :pretitle="trans('admin.title')"/>
    </div>
    <div class="col-12">
        <x:form::form method="PUT" class="card" :action="route('admin.'.$routePrefix.'.update',request()->route()->parameters)" :bind="$item" enctype="multipart/form-data">
            <div class="card-body">
                @includeIf($module.'::admin.'.$routePrefixSingular.'.form')
            </div>
            <div class="card-footer text-end">
                <div class="d-flex">
                    <button type="submit" class="btn btn-primary me-auto">@lang('admin.submit')</button>
                    @if(Route::has('admin.'.$routePrefix.'.index'))
                        <a href="{{route('admin.'.$routePrefix.'.index',request()->route()->parameters)}}" class="btn btn-link">@lang('admin.cancel')</a>
                    @endif
                </div>
            </div>
            </x:form:form>
    </div>
@endsection
