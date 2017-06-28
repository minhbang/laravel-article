@extends('backend.layouts.master')
@section('content')
    <div class="ibox ibox-table">
        <div class="ibox-title">
            <h5>{!! trans('article::common.manage_title', ['name' => $typeName]) !!}</h5>
            <div class="buttons">
                {!! Html::linkButton('#', trans('common.filter'), ['class'=>'advanced_filter_collapse','type'=>'info', 'size'=>'xs', 'icon' => 'filter']) !!}
                {!! Html::linkButton('#', trans('common.all'), ['class'=>'advanced_filter_clear', 'type'=>'warning', 'size'=>'xs', 'icon' => 'list']) !!}
                {!! Html::linkButton(route('backend.article.create'), trans('common.create'), ['type'=>'success', 'size'=>'xs', 'icon' => 'plus-sign']) !!}
            </div>
        </div>
        <div class="ibox-content">
            <div class="bg-warning dataTables_advanced_filter hidden">
                <form class="form-horizontal" role="form">
                    {!! Form::hidden('filter_form', 1) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('filter_created_at', trans('common.created_at'), ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-9">
                                    {!! Form::daterange('filter_created_at', [], ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('filter_updated_at', trans('common.updated_at'), ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-9">
                                    {!! Form::daterange('filter_updated_at', [], ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            {!! $html->table(['id' => 'article-manage']) !!}
        </div>
    </div>
@stop

@push('scripts')
<script type="text/javascript">
    window.datatableDrawCallback = function (dataTableApi) {
        dataTableApi.$('a.quick-update').quickUpdate({
            'url': '{{ route('backend.article.quick_update', ['article' => '__ID__']) }}',
            'container': '#article-manage',
            'dataTableApi': dataTableApi
        });
        /*dataTableApi.$('select.select-btngroup').select_btngroup({
            dataTable: dataTableApi
        });*/
    };
    window.settings.mbDatatables = {
        trans: {
            name: '{{trans('article::common.article')}}'
        }
    }
</script>
{!! $html->scripts() !!}
@endpush