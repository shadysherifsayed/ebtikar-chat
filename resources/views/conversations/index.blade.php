@extends('layouts.app')

@section('content')

<div class="row chat-page">
    <div class="col-3 conversations px-4">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <button class="btn btn-primary" id="user-search" data-input="user-search-input">
                    Search
                </button>
            </div>
            <input type="text" class="form-control" id="user-search-input" placeholder="Search For User" aria-describedby="user-seach">
            <div class="input-group-append">
                <button class="btn btn-secondary" id="clear-user-search" data-input="user-search-input">X</button>
            </div>
        </div>

        <div class="user-conversations">
            @if(count($conversations))
            @foreach ($conversations as $userConversation)
            <a class="conversation shadow-sm my-2 d-block {{ $conversation->is($userConversation) ? 'active' : '' }}" href="{{ route('conversations.show', $userConversation) }}">
                {{ $userConversation->name() }}
            </a>
            @endforeach
            @else
            <h2 class="text-center m-0 bg-dark text-white p-2"> There are no conversations yet </h2>
            @endif
        </div>

        <div class="user-search"></div>

    </div>

    <div class="col-9 messages px-4">
        @if($conversation)
            <div class="conversation-name bg-dark text-white py-2 text-center shadow-sm d-flex align-items-center justify-content-between px-5">
                <h3 class="m-0">
                    {{ $conversation->name() }}
                </h3>
                <form action="{{ route('conversations.leave', $conversation) }}" method="post">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger"> Clear Chat </button>
                </form>
            </div>
            <div class="conversation-messages">
                @foreach ($conversation->messages as $message)
                    <div class="shadow-sm my-2 p-2 message">
                        <h3 class="m-0"> {{ $message->body }} </h3>
                        <div>
                            <small> {{ $message->sender->name }} ({{ $message->time_difference }}) </small>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="new-message">
                <form action="{{ route('messages.store', $conversation) }}" method="post">
                    @csrf
                    @if ($errors->has('message'))
                       <span class="invalid-feedback d-block" role="alert">
                           <strong> Message cannot be empty </strong>
                       </span>
                   @endif
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <button class="btn btn-primary">
                                Send
                            </button>
                        </div>
                        <input type="text" name="message" class="form-control" placeholder="Type Your Message">
                    </div>
                </form>
            </div>
        @else
        <h2 class="text-center m-0 bg-dark text-white p-2"> There are no conversations yet </h2>
        @endif
    </div>
</div>
@endsection

@push('js')
    <script src="{{ asset('js/main.js') }}"></script>
@endpush
