<div id="header">
<div class="container">
<nav class="navbar navbar-default " role="navigation" id="main-nav">
    <a class="hidden-lg hidden-md" href=""><img src="/images/logo.png" class="center-block"></a>
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".main-nav-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand hidden-sm hidden-xs logo-lg-screen" href=""><img src="/images/logo.png"></a>
        </div>

    <div class="collapse navbar-collapse main-nav-collapse">
        <ul class="nav navbar-nav">
            @if(URL::current() == URL::route('show_homepage'))
                <li class="active">
            @else
                <li>
            @endif
            <a href="/">OPAC</a></li>

            @if (Auth::check())
                    @if(URL::current() == URL::route('admin.transaction.index'))
                        <li class="active">
                    @else
                        <li>
                    @endif
                    <a href="{{url('admin/transaction')}}">TRANSACTIONS</a></li>

                @if (Auth::user()->hasRole('admin'))
                    @if (URL::current() == URL::route('admin.transaction.issue-books'))
                        <li class="active">
                    @else
                        <li>
                    @endif
                        {{--<a href="/admin/transaction/issue-books">ISSUE BOOKS</a></li>--}}
                    {{--@if(URL::current() == URL::route('admin.users.index') || URL::current() == URL::route('admin.users.create'))--}}
                        {{--<li class="active">--}}
                    {{--@else--}}
                        {{--<li>--}}
                    {{--@endif--}}
                    <li><a href="{{url('admin/users')}}">USERS</a></li>

                    @if(URL::current() == URL::route('admin.books.index') || URL::current() == URL::route('admin.books.create') || URL::current() == URL::route('admin.materials.index') || URL::current() == URL::route('admin.materials.create'))
                        <li class="active">
                    @else
                        <li>
                    @endif
                    <a href="{{url('admin/books')}}">BOOKS</a></li>

                    <li><a href="{{url('admin/reports')}}">REPORTS</a></li>

                @endif


                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">SETTINGS <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/change-password">Change Password</a></li>
                    </ul>

                </li>

                <li><a href="/logout">
                        LOGOUT
                        <?php
                        if (session()->has('current_url')) {
                            session()->forget('current_url');
                        }
                        ?>
                    </a>
                </li>
            @else
                <li><a href="/login">LOGIN</a></li>
            @endif
        </ul>
    </div><!-- /.navbar-collapse -->

        {{--<div id="navbar" class="navbar-collapse collapse">--}}
            {{--<ul class="nav navbar-nav navbar-right">--}}

                {{--<li><a href="/">Dashboard</a></li>--}}

                {{--<li><a href="/sms/create">SMS Simulator</a></li>--}}


                {{--@if (Auth::check())--}}
                {{--<li class="dropdown">--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"--}}
                       {{--aria-expanded="false">Profile <span class="caret"></span></a>--}}
                    {{--<ul class="dropdown-menu">--}}
                        {{--<li><a href="/">{{ Auth::user()->name }}</a></li>--}}
                        {{--<li role="separator" class="divider"></li>--}}
                        {{--<li><a href="/logout">Logout</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                {{--@else--}}
                    {{--<li><a href="/login">Login</a></li>--}}
                {{--@endif--}}
            {{--</ul>--}}
        {{--</div>--}}
    {{--</div>--}}
</nav>
</div>
</div>

