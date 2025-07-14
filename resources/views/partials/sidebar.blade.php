<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="{{ route('dashboard') }}" class="sidebar-logo">
            <img src="{{ asset('wowdash/images/logo.png') }}" alt="site logo" class="light-logo">
            <img src="{{ asset('wowdash/images/logo-light.png') }}" alt="site logo" class="dark-logo">
            <img src="{{ asset('wowdash/images/logo-icon.png') }}" alt="site logo" class="logo-icon">
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