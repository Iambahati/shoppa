<?php

namespace App\View\Components\Nav;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Topbar extends Component
{
    public int $unreadCount;
    public Collection $notifications;

    public function __construct(public bool $staff = false)
    {
        $user = auth()->user();

        if ($user) {
            $this->unreadCount   = $user->unreadNotifications()->count();
            $this->notifications = $user->unreadNotifications()->latest()->take(8)->get();
        } else {
            $this->unreadCount   = 0;
            $this->notifications = new Collection();
        }
    }

    public function render(): View|Closure|string
    {
        return view('components.nav.topbar');
    }
}
