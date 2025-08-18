<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="{{ route('dashboard') }}" class="sidebar-logo">
            <img src="{{ asset('wowdash/images/fph-ci.png') }}" alt="FPH-CI Logo" class="light-logo" style="max-width: 150px; height: auto;">
            <img src="{{ asset('wowdash/images/fph-ci.png') }}" alt="FPH-CI Logo" class="dark-logo" style="max-width: 150px; height: auto;">
            <img src="{{ asset('wowdash/images/fph-ci.png') }}" alt="FPH-CI Logo" class="logo-icon" style="max-width: 40px; height: auto;">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            @foreach($navigation as $item)
                @if($item['type'] == 'title')
                    <li class="sidebar-menu-group-title">{{ $item['title'] }}</li>
                @else
                    <li>
                        <a href="{{ $item['url'] }}" class="{{ $item['active'] ? 'active' : '' }}">
                            <i class="{{ $item['icon'] }} menu-icon"></i>
                            <span>{{ $item['title'] }}</span>
                            @if(isset($item['badge']))
                                <span class="badge bg-danger ms-auto">{{ $item['badge'] }}</span>
                            @endif
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</aside> 