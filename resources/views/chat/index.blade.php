@extends('layouts.app')
@section('content')
    @include('chat.invite_modal')
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box page-title-box-alt">
                        <h4 class="page-title">Chat</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">{{ __('messages.dcq') }}</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                <li class="breadcrumb-item active">Chat</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <!-- start chat users-->
                <div class="col-xl-3 col-lg-4">
                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex align-items-start align-items-start mb-3">
                                <img src="/assets/images/users/avatar-1.jpg" class="me-2 rounded-circle" height="42"
                                    alt="Brandon Smith">
                                <div class="flex-1">
                                    <h5 class="mt-0 mb-0 font-15">
                                        <a href="contacts-profile.html" class="text-reset">{{ Auth::user()->name }}</a>
                                    </h5>
                                    <p class="mt-1 mb-0 text-muted font-14">
                                        <small class="mdi mdi-circle text-success"></small> Online
                                    </p>
                                </div>
                                <div>
                                    <a href="javascript: void(0);" class="text-reset font-20">
                                        <i class="mdi mdi-cog-outline"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- start search box -->
                            <form method="get" action="{{ route('chat.index') }}" id="task_form" accept-charset="UTF-8"
                                enctype="multipart/form-data">
                                <div class="position-relative" style="display: flex">
                                    <input name="search" type="text" class="form-control form-control-light"
                                        value="{{ $_GET['search'] ?? '' }}" placeholder="People, groups & messages...">
                                    <span class="mdi mdi-magnify"
                                        style="
    position: absolute;
    right: .3rem;
    top: .5rem;
"></span>
                                </div>
                            </form>
                            <!-- end search box -->

                            <h6 class="font-13 text-muted text-uppercase mb-2">Contacts</h6>

                            <!-- users -->
                            <div class="row">
                                <div class="col">


                                    <div data-simplebar style="max-height: 498px">
                                        {{-- own chat room --}}
                                        @isset($userRooms)

                                            @forelse ($userRooms as $roomId  =>  $room)
                                                <a href="{{ route('chat.room', ['room_id' => $roomId ?? mt_rand()]) }}"
                                                    class="text-body">
                                                    <div class="d-flex align-items-start p-2">
                                                        <div class="position-relative">
                                                            <span class="user-status"></span>
                                                            <img src="/assets/images/users/avatar-2.jpg"
                                                                class="me-2 rounded-circle" height="42" alt="user" />
                                                        </div>
                                                        <div class="flex-1">
                                                            <h5 class="mt-0 mb-0 font-14">
                                                                <span class="float-end text-muted fw-normal font-12">
                                                                    @if (isset($room['latest_chat']->created_at))
                                                                    {{ \Carbon\Carbon::parse($room['latest_chat']->created_at ?? '0')->setTimezone('Asia/Ho_Chi_Minh')->format('h:i A') ?? '00:00' }}
                                                                    @else
                                                                    {{ \Carbon\Carbon::parse($room->latestChat->created_at ?? '0')->setTimezone('Asia/Ho_Chi_Minh')->format('h:i A') ?? '00:00' }}
                                                                    @endif
                                                                </span>
                                                                {{-- room name --}}
                                                                @if ($room['users']->count() > 2)
                                                                    {{-- This is a group room --}}

                                                                    @if ($room['users'][0]->room_name ?? false)
                                                                        {{-- Group room has a custom room name --}}
                                                                        @php
                                                                            $roomName = $room['users'][0]->room_name;
                                                                        @endphp
                                                                    @else
                                                                        {{-- Room name is all users' names in this room --}}
                                                                        @php
                                                                            $roomName = $room['users']->implode('name', ', ');
                                                                        @endphp
                                                                    @endif
                                                                @elseif ($room['users']->count() > 1)
                                                                    {{-- This is a private room --}}
                                                                    @if ($room['users']->contains('id', Auth::user()->id))
                                                                        {{-- Auth user is part of the private room --}}
                                                                        @php
                                                                            $otherUsers = $room['users']->where('id', '!=', Auth::user()->id);
                                                                            $roomName = $otherUsers->implode('name', ', ');
                                                                        @endphp
                                                                    @else
                                                                        {{-- Auth user is not part of the private room --}}
                                                                        @php
                                                                            $roomName = $room['users']->implode('id', ', ');
                                                                        @endphp
                                                                    @endif
                                                                @else
                                                                    {{-- Default room name --}}
                                                                    @php
                                                                        $roomName = $room['latest_chat']->note;
                                                                    @endphp
                                                                @endif

                                                                {{ $roomName }}
                                                            </h5>
                                                            <p class="mt-1 mb-0 text-muted font-14">
                                                                <span class="w-25 float-end text-end"><span
                                                                        class="badge badge-soft-danger">3</span></span>

                                                                <span
                                                                    class="w-75">{{ $room['latest_chat']->note ?? 'Start new conversation' }}</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </a>
                                            @empty
                                                <p>Nothing</p>
                                            @endforelse
                                        @endisset


                                        @isset($sortedUsers)
                                            @forelse ($sortedUsers as $user)
                                            @if (  $user->id == Auth::user()->id)
                                            @continue
                                           @endif
                                                <a href="{{ route('chat.room', ['room_id' => $user ?? mt_rand()]) }}"
                                                    class="text-body">
                                                    <div class="d-flex align-items-start p-2">
                                                        <div class="position-relative">
                                                            <span class="user-status"></span>
                                                            <img src="/assets/images/users/avatar-2.jpg"
                                                                class="me-2 rounded-circle" height="42" alt="user" />
                                                        </div>
                                                        <div class="flex-1">
                                                            <h5 class="mt-0 mb-0 font-14">
                                                                <span class="float-end text-muted fw-normal font-12">
                                                                    {{ \Carbon\Carbon::parse($room->latestChat->created_at ?? '0')->setTimezone('Asia/Ho_Chi_Minh')->format('h:i A') ?? '00:00' }}
                                                                </span>
                                                                {{-- room name --}}
                                                                @php
                                                                    $roomName = '';
                                                                    if ($user->chatRoom && $user->chatRoom->room_name) {
                                                                        // Group room has a custom room name
                                                                        $roomName = $user->chatRoom->room_name;
                                                                    } elseif ($user->chatRoom && $user->chatRoom->users->count() > 1) {
                                                                        // This is a private room
                                                                        if ($user->chatRoom->users->contains('id', Auth::user()->id)) {
                                                                            // Auth user is part of the private room
                                                                            $otherUsers = $user->chatRoom->users->where('id', '!=', Auth::user()->id);
                                                                            $roomName = $otherUsers->implode('name', ', ');
                                                                        } else {
                                                                            // Auth user is not part of the private room
                                                                            $roomName = $user->chatRoom->users->implode('name', ', ');
                                                                        }
                                                                    } else {
                                                                        // Default room name
                                                                        $roomName = $user->latestChat->note ?? '';
                                                                    }
                                                                @endphp

                                                                {{ $roomName }}
                                                            </h5>
                                                            <p class="mt-1 mb-0 text-muted font-14">
                                                                <span class="w-25 float-end text-end"><span
                                                                        class="badge badge-soft-danger">3</span></span>
                                                                <span
                                                                    class="w-75">{{ $room->latestChat->note ?? 'Start new conversation' }}</span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                </a>
                                            @empty
                                                <p>Nothing</p>
                                            @endforelse
                                        @endisset
                                        {{-- search public room --}} {{-- search private chat room --}}

                                        @isset($searchUsers)

                                        @if (!isset($searchUsers[0]))
                                        <p>User Not Found</p>

                                        @endif
                                            @forelse ($searchUsers as $user)
                                          @if ($user['id'] == Auth::user()->id)
                                             @continue
                                          @else

                                          @endif
                                           @if (!isset($user->id) ?? $user->id == Auth::user()->id)
                                            @continue
                                           @endif
                                                <?php

                                                if (isset($user->chatRoom[0])) {
                                                    $roomId = $user->chatRoom[0]->room_id;
                                                } else {
                                                    $roomId = mt_rand();
                                                }
                                                ?>
                                                @if (!isset($user->id))
                                                    @continue
                                                @endif
                                                @if (isset($user->chatRoom[0]) && !isset($user['room_type']))
                                                    <a href="{{ route('chat.room', ['room_id' => $roomId]) }}"
                                                        class="text-body">
                                                    @elseif(!isset($user['room_type']))
                                                        <a href="{{ route('chat.room', ['room_id' => $roomId, 'otherId' => $user->id]) }}"
                                                            class="text-body">
                                                @endif
                                                <div class="d-flex align-items-start p-2">
                                                    <div class="position-relative">
                                                        <span class="user-status"></span>
                                                        <img src="/assets/images/users/avatar-2.jpg" class="me-2 rounded-circle"
                                                            height="42" alt="user" />
                                                    </div>
                                                    <div class="flex-1">
                                                        <h5 class="mt-0 mb-0 font-14">
                                                            <span class="float-end text-muted fw-normal font-12">
                                                                {{ \Carbon\Carbon::parse($room->latestChat->created_at ?? '0')->setTimezone('Asia/Ho_Chi_Minh')->format('h:i A') ?? '00:00' }}
                                                            </span>
                                                            {{-- room name --}}
                                                            @php
                                                                $roomName = '';
                                                                if (isset($user->chatRoom[0]) && $user->chatRoom[0]->room_name) {
                                                                    // Group room has a custom room name
                                                                    $roomName = $user->chatRoom[0]->room_name ?? '';
                                                                } elseif (isset($user->chatRoom[0])) {
                                                                    // This is a private room

                                                                    // Auth user is part of the private room
                                                                    $otherUsers = $user->name;
                                                                    $roomName = $otherUsers;
                                                                } else {
                                                                    // Default room name
                                                                    $roomName = $user->name ?? '';
                                                                }
                                                            @endphp

                                                            {{ $roomName }}
                                                        </h5>
                                                        <p class="mt-1 mb-0 text-muted font-14">
                                                            <span class="w-25 float-end text-end"><span
                                                                    class="badge badge-soft-danger">3</span></span>

                                                            @if ($user->latestChat->note ?? null)
                                                                <span
                                                                    class="w-75">{{ $user->latestChat->note ?? 'Start new conversation' }}</span>
                                                            @else
                                                                <span
                                                                    class="w-75">{{ $user->latestChat ?? 'Start new conversation' }}</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>

                                                </a>


                                            @empty
                                                <p>Nothing</p>
                                            @endforelse
                                        @endisset


                                    </div> <!-- end slimscroll-->
                                </div> <!-- End col -->
                            </div>
                            <!-- end users -->
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div>
                <!-- end chat users-->

                <!-- chat area -->
                <div class="col-xl-9 col-lg-8">
                    {{-- @isset($otherUser) --}}
                    <div class="card">
                        <div class="card-body py-2 px-3 border-bottom border-light">
                            <div class="d-flex py-1">
                                <img src="/assets/images/users/avatar-5.jpg" class="me-2 rounded-circle" height="36"
                                    alt="Brandon Smith">
                                <div class="flex-1">
                                    <h5 class="mt-0 mb-0 font-15">
                                        @if ( isset($userRooms[$room_id]) && $userRooms[$room_id]['users'] && count($userRooms[$room_id]['users']) > 2)
                                            <?php
                                            $userNames = '';

                                            foreach ($userRooms[$room_id]['users'] as $index => $user) {
                                                $userName = $user->name;
                                                $userNames .= $userName . ', ';
                                            }

                                            $userNames = rtrim($userNames, ', ');

                                            $roomNameByRoomId = $userNames;
                                            ?>
                                            <a href="contacts-profile.html"
                                                class="text-reset">{{ $roomNameByRoomId ?? $searchUsers[0]->name }}</a>
                                        @else
                                        @if (isset($roomNameByRoomId) && $roomNameByRoomId != '')
                                        <a href="contacts-profile.html"
                                        class="text-reset">{{  $roomNameByRoomId  }}</a>
                                        @else
                                        <a href="contacts-profile.html"
                                        class="text-reset">{{ isset($searchUsers[0]) ? ($roomNameByRoomId ?? $searchUsers[0]->name) : 'Not Found' }}</a>
                                        @endif

                                        @endif
                                    </h5>
                                    <p class="mt-1 mb-0 text-muted font-12">
                                        <small class="mdi mdi-circle text-success"></small> Online
                                    </p>
                                </div>
                                <div id="tooltip-container">

                                    <button id="show_invite_modal" data-toggle="modal" data-target="#exampleModalCenter"
                                        class="text-reset font-19 py-1 px-2 d-inline-block">
                                        <i class="fe-user-plus" data-bs-container="#tooltip-container"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Add Users"></i>
                                    </button>

                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="conversation-list chat-app-conversation" id="chat-container" data-simplebar
                                style="max-height: 460px">

                                @isset($chats)
                                    @forelse ($chats as $chat)

                                        @if ($chat->sender_id != Auth::user()->id)
                                            <li class="clearfix">
                                                <div class="chat-avatar">
                                                    <img src="/assets/images/users/avatar-5.jpg" class="rounded"
                                                        alt="James Z" />
                                                    <i>{{ \Carbon\Carbon::parse($chat->created_at)->setTimezone('Asia/Ho_Chi_Minh')->hour . ':' . \Carbon\Carbon::parse($chat->created_at)->setTimezone('Asia/Ho_Chi_Minh')->minute ?? '00:00 ' }}</i>
                                                </div>
                                                <div class="conversation-text">
                                                    <div class="ctext-wrap">
                                                        <i>{{ $chat->name }}</i>


                                                        @isset($chat->file_url)
                                                        <a href="{{$chat->file_url}}" download="">
                                                            <p>
                                                                {{ $chat->note }}
                                                            </p>
                                                        </a>
                                                        @else
                                                        <p>
                                                            {{ $chat->note }}
                                                        </p>
                                                        @endisset

                                                    </div>
                                                </div>

                                                <div class="conversation-actions dropdown">
                                                    <button class="btn btn-sm btn-link text-reset" data-bs-toggle="dropdown"
                                                        aria-expanded="false"><i
                                                            class='mdi mdi-dots-vertical font-18'></i></button>

                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Copy Message</a>
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </li>
                                        @else
                                            <li class="clearfix odd">
                                                <div class="chat-avatar">
                                                    <img src="/assets/images/users/avatar-1.jpg" class="rounded"
                                                        alt="Nik Patel" />
                                                    <i>{{ \Carbon\Carbon::parse($chat->created_at)->setTimezone('Asia/Ho_Chi_Minh')->hour . ':' . \Carbon\Carbon::parse($chat->created_at)->setTimezone('Asia/Ho_Chi_Minh')->minute ?? '00:00 ' }}</i>
                                                </div>
                                                <div class="conversation-text">
                                                    <div class="ctext-wrap">
                                                        <i>{{ Auth::user()->name }}</i>
                                                        @isset($chat->file_url)
                                                        <a href="{{$chat->file_url}}" download="">
                                                            <p>
                                                                {{ $chat->note }}
                                                            </p>
                                                        </a>
                                                        @else
                                                        <p>
                                                            {{ $chat->note }}
                                                        </p>
                                                        @endisset
                                                    </div>
                                                </div>
                                                <div class="conversation-actions dropdown">
                                                    <button class="btn btn-sm btn-link text-reset" data-bs-toggle="dropdown"
                                                        aria-expanded="false"><i
                                                            class='mdi mdi-dots-vertical font-18'></i></button>

                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Copy Message</a>
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    @empty
                                        <p>Bắt đầu cuộc trò chuyện</p>
                                    @endforelse
                                @endisset
                            </ul>
                            <script>
                                $(document).ready(function() {
                                    $(window).on('load', function() {
                                        // Tự động kéo xuống chat cuối cùng
                                        let test = document.getElementsByClassName('simplebar-content-wrapper')[2]
                                        test.scrollTop = test.scrollHeight;
                                    })
                                });
                            </script>
                            <div class="row">
                                <div class="col">
                                    <div class="mt-2 bg-light p-3 rounded">
                                        <form method="post" action="{{ route('chat.add') }}" id="task_form" rou
                                            accept-charset="UTF-8" enctype="multipart/form-data" id="chat-form">
                                            @csrf
                                            <div class="row">

                                                <div class="col mb-2 mb-sm-0">
                                                    <input type="hidden" name="room_id" value="{{ $room_id ?? null }}">

                                                    <input type="file" name="file_chat" id="file_chat" hidden>
                                                    <input type="text" name="note" id="note" class="form-control border-0" placeholder="Enter your text" required="">
                                                    <div class="invalid-feedback mt-2">
                                                        Please enter your messsage
                                                    </div>
                                                </div>

<script>
    const fileInput = document.getElementById('file_chat');
    const noteInput = document.getElementById('note');

    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            noteInput.value = file.name;
        }
    });
</script>
                                                <div class="col-sm-auto">
                                                    <div class="btn-group">
                                                        <label for="file_chat" href="#" class="btn btn-light"><i
                                                                class="fe-paperclip"></i></label>
                                                        <div class="d-grid">
                                                            <button type="submit" class="btn btn-success chat-send"><i
                                                                    class='fe-send'></i></button>
                                                        </div>
                                                    </div>
                                                </div> <!-- end col -->
                                            </div> <!-- end row-->
                                        </form>
                                    </div>
                                </div> <!-- end col-->
                            </div>
                            <!-- end row -->
                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                    {{-- @endisset --}}

                </div>
                <!-- end chat area-->

            </div> <!-- end row-->

        </div> <!-- container -->

    </div> <!-- content -->

    <!-- Footer Start -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <script>
                        document.write(new Date().getFullYear())
                    </script> &copy; Minton theme by <a href="">Coderthemes</a>
                </div>
                <div class="col-md-6">
                    <div class="text-md-end footer-links d-none d-sm-block">
                        <a href="javascript:void(0);">About Us</a>
                        <a href="javascript:void(0);">Help</a>
                        <a href="javascript:void(0);">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end Footer -->
@endsection
