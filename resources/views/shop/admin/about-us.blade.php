@extends('master')
@section('body')
<div class="container col-8">
    <h3>O nas</h3>
    <hr>
    <h6>Sme internetovy obchod ktory nema kamennu predajnu, ponukane produkty su len ilustracne a cela tato stranka je len pre studijne ucely</h6>
    <br>
    <br>
    @foreach($comments as $comment)
        <div class="comments">
            <li class="list-group-item">
                <b>{{$comment->author}}</b>
                <b>{{$comment->created_at}}</b>
                {{ $comment->body }}
                <form id="comment-delete" action="{{route('admin.about.us.delete.comment',$comment->id)}}" method="POST">
                    {{csrf_field()}}
                    <button type="submit" class="btn btn-dark"><i class="far fa-trash-alt"></i></button>
                </form>

            </li>
        </div>
    @endforeach
    @if(auth()->check())
        <form method="POST" action="{{route('about.us.add.comment')}}">
            {{ csrf_field() }}
            <div class="form-group">
                <textarea name="body" placeholder="Add Comment." class="form-control" required ></textarea>
            </div>
            <button type="submit" class="btn btn-dark">@lang('message.add_comment')</button>
        </form>
    @endif
</div>
@endsection