<!-- Created: 3/8/2018
    by: Lucas Kaastra
    Modified:
 -->

    <!-- Styles -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">

<div class="dashboardsidebar">
    {{--<img src="#" alt="Profile Picture">--}}
    <a href="/dashboard">Dashboard</a>
    <a href="/funds">Funds</a>
    <a href="/coinlookup">Coin Lookup</a>
    <a href="/profile">Profile</a>
    <a href="{{ route('logout') }}"
            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
            Logout
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
</div>