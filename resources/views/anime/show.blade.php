@extends('base')

@section('content')

<div class="row">
    <div class="col-md-3 mx-auto mb-2">
        <img src="{{ url("storage/{$anime->image}") }}" class="img-fluid rounded-start" alt="">
    </div>
    <div class="col-md-5">
        <div>
            <span class="h3">{{ $anime->title }}</span>
            <br>
            <span class="badge bg-primary">{{ $anime->statu->name }}</span>
            <i class="fa fa-star text-warning"></i> {{ $anime->score }}
        </div>
        <div class="mt-2">
            <strong>Synopsis: </strong> <br>
            <span>{{ $anime->synopsis }}</span>
        </div>
        {{-- <div class="mt-2">
            <strong>Episodes: </strong> {{ $anime->episodes }}
        </div> --}}
        <div class="row mt-2">
            <div class="col-sm-6">
                <strong>Studio: </strong><a class="link-studio" href="{{ route('studios.show', $anime->studio->id) }}">{{ $anime->studio->name }}</a>
            </div>
            <div class="col-sm-6 text-sm-right">
                <strong>Source: </strong>{{ $anime->source }}
            </div>
        </div>
        <ul class="nav justify-content-center mt-3">
            <li class="nav-item">
                <a href="{{ route('animes.index') }}" class="button-18 color-default">Show</a>
            </li>

                &nbsp;
                <li class="nav-item">
                    <a class="button-18 color-default" href="{{ route('animes.edit', $anime->id) }}">Edit</a>
                </li>
                &nbsp;<li class="nav-item">
                    <form action="{{ route('animes.destroy', $anime->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <input class="button-18 color-remove" type="submit" value="Delete"> <!--here-->
                    </form>
                </li>

        </ul>
    </div>
    <div class="col-md-4">
        <h3>Ratings</h3>
        <hr>
        <form method="post" action="{{ isset($comment->id) ? route('comments.update', $comment->id) : route('animes.comments.store', $anime->id) }}">
            @csrf
            @if(isset($comment->id))
                @method('PATCH')
            @endif
            <label for="body">Make a comment:</label>
            <div class="input-group">
                <input name="body" id="body" class="form-control col-10" value="{{ $comment->body }}"/>
                <button type="submit" class="btn btn-primary col-2">
                    <i class="fa fa-send"></i>
                </button>
            </div>
        </form>
        <h4>Comments</h4>
        <div class="comment-section overflow-auto mt-3" style="max-height: 450px;">
            @foreach($anime->comments as $comment)
    <div class="card border border-3 border-secondary mb-3">
        <div class="card-body">
            {{-- Comment body --}}
            <p class="mt-2">{{ $comment->body }}</p>

            <div class="d-flex align-items-center mt-2">
                {{-- Edit and delete buttons --}}
                <div class="ms-auto">
                    <form action="{{ route('comments.edit', $comment->id) }}" method="get" style="display: inline;">
                        <button type="submit" class="btn btn-primary btn-sm" style="border-radius: 30px;">Edit</button>
                    </form>
                    <form action="{{ route('comments.delete', $comment->id) }}" method="post" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" style="border-radius: 30px;">Delete</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
            @endforeach
</div>
</div>
<div class="mb-3">
    <h3 class="mb-3">Episodes</h3>
    @foreach($anime->episodes as $episode)
        <div class="card border border-3 border-secondary mb-3">
            <div class="card-body">
                <h5 class="card-title">Episode {{ $episode->number }}: {{ $episode->title }}</h5>
                <p class="card-text">Air Date: {{ $episode->air_date ?? 'N/A' }}</p>
                <p class="card-text">Summary: {{ $episode->summary ?? 'N/A' }}</p>
                <a href="https://www.crunchyroll.com/" class="btn btn-success" target="_blank">Watch on Crunchyroll</a>
            </div>
        </div>
    @endforeach
</div>


    <div class="mt-3">
        <a href="{{ route('episodes.create', ['anime_id' => $anime->id]) }}" class="btn btn-success">Add New Episode</a>
    </div>

</div>

@endsection
{{-- <img src="{{ url("storage/images/{$comment->user->avatar}") }}" class="rounded-circle" width="50" height="50" alt="">
                <div class="mx-3 fw-bold">{{ $comment->user->name }}</div> --}}
