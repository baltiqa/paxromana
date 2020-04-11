<div class="dashboard-header">
    <nav class="navbar navbar-expand-lg bg-white fixed-top">
        <a class="navbar-brand" href="/panel">Tenebris</a>
        <button class="navbar-toggler" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse " id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto navbar-right-top">
            <li class="nav-item dropdown nav-user">
                   <a class="dropdown-item" href="/"><i class="fas fa-power-off mr-2"></i>Back to Market</a>
             </li>
                <li class="nav-item dropdown nav-user">
                        <div style="background-color: black;" class="nav-user-info">
                        <h5 class="mb-0 text-white nav-user-name">@if(auth()->user()->is_admin == 1 && auth()->user()->is_headmod == 1 && auth()->user()->is_mod == 1) Administrator @elseif(auth()->user()->is_admin == 0 && auth()->user()->is_headmod == 1) Head Moderator @elseif(auth()->user()->is_mod == 1) Moderator @endif</h5>
                        </div>
                </li>
                <li class="nav-item dropdown nav-user">
                        <div class="nav-user-info">
                            <h5 class="mb-0 text-white nav-user-name">{{ auth()->user()->username }}</h5>
                        </div>
                </li>
                <li class="nav-item dropdown nav-user">
                    <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2"><img src="{{auth()->user()->getAvatar()}}" alt=""
                            class="user-avatar-md"></a>
                </li>

            </ul>
        </div>
    </nav>
</div>
