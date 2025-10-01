<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CustomerNavigation extends Component
{
    public $currentRoute;
    
    public function __construct($currentRoute = null)
    {
        $this->currentRoute = $currentRoute ?? request()->route()->getName();
    }

    public function render()
    {
        return view('components.customer-navigation');
    }
    
    public function menuItems()
    {
        return [
            [
                'name' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'home',
                'active' => $this->currentRoute === 'dashboard'
            ],
            [
                'name' => 'My Orders',
                'route' => 'orders.index',
                'icon' => 'shopping-bag',
                'active' => str_contains($this->currentRoute, 'orders.index')
            ],
            // [
            //     'name' => 'Wishlist',
            //     'route' => 'customer.wishlist',
            //     'icon' => 'heart',
            //     'active' => str_contains($this->currentRoute, 'wishlist')
            // ],
            [
                'name' => 'Addresses',
                'route' => 'addresses.index',
                'icon' => 'map-pin',
                'active' => str_contains($this->currentRoute, 'addresses.index')
            ],
            [
                'name' => 'Profile',
                'route' => 'profile',
                'icon' => 'user',
                'active' => str_contains($this->currentRoute, 'profile')
            ],
            // [
            //     'name' => 'Settings',
            //     'route' => 'settings',
            //     'icon' => 'settings',
            //     'active' => str_contains($this->currentRoute, 'settings')
            // ],
         [
    'name' => 'Logout',
    'route' => 'logout',
    'icon' => 'log-out',
    'active' => false
]

            
        ];
    }
}
