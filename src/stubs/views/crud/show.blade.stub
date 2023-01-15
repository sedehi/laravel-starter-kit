@extends('tabler::layout')
@section('content')
    <div class="page-header">
        <div class="row align-items-center">
            @sectionMissing('page_title')
                <x-tabler::page-title :title="trans('admin.'.$routePrefix)" :pretitle="trans('admin.title')"/>
                @else
                    @yield('page_title')
                @endif
                <div class="col-auto ms-auto">
                    <div class="btn-list">
                        <form method="post" action="{{route('admin.'.$routePrefix.'.destroy',$item->id)}}">
                            @csrf
                            @method('delete')
                            <input type="hidden" name="id[]" value="{{$item->id}}">
                            <button class="btn btn-danger" type="submit">@lang('admin.destroy')</button>
                        </form>
                        <a href="{{route('admin.'.$routePrefix.'.edit',$item->id)}}" class="btn btn-warning">@lang('admin.edit')</a>
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
@endsection
