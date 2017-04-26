@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('admin.sidebar')

        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">Posts</div>
                <div class="panel-body">
                    {!! Form::open(['method' => 'GET', 'url' => '/admin/posts', 'class' => 'navbar-form',
                    'role' => 'search']) !!}
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search...">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                    </div>
                    {!! Form::close() !!}

                    <br/>
                    <br/>

                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                            <tr>
                                <th><h2>My Blog Laravel test</h2></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($posts as $item)
                            <tr>
                                <td>
                                    <div><a href="{{ url('/admin/posts/' . $item->id) }}" title="View post">{{$item->title}}</a>
                                        <br/>
                                        <small>
                                            {{ $item->created_at }}
                                        </small>
                                    </div>
                                    <div>{{ $item->content }}</div>
                                    <div><img src="/uploads/{{ $item->image }}" width="150px"></div>
                                    <div style="text-align: right;"><a href="{{ url('/admin/posts/' . $item->id) }}"
                                                                       title="View post" class="btn btn-success btn-sm">Read More</a></div>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="pagination-wrapper" style="text-align: center">
                            {!! $posts->appends(['search' => Request::get('search')])->render() !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
