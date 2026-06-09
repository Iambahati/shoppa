<?php

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\User;
use App\Notifications\ShoppaNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('notifications')->truncate();

        User::with('role')->each(function (User $user) {
            $role = $user->roleName();
            if (! $role) return;

            foreach ($this->notificationsFor($role) as $n) {
                $notification = $user->notify(new ShoppaNotification(
                    title:    $n['title'],
                    message:  $n['message'],
                    url:      $n['url'],
                    icon:     $n['icon'],
                    priority: $n['priority'],
                ));
            }
        });
    }

    private function notificationsFor(RoleName $role): array
    {
        return match (true) {
            in_array($role, [RoleName::SuperAdmin, RoleName::Admin]) => [
                [
                    'title'    => '8 vendor applications awaiting review',
                    'message'  => 'New seller applications need approval before they can list devices.',
                    'url'      => '/admin/vendors',
                    'icon'     => 'store',
                    'priority' => 'warning',
                ],
                [
                    'title'    => '3 disputes require urgent resolution',
                    'message'  => 'Open disputes have been escalated and need immediate attention.',
                    'url'      => '/admin/disputes',
                    'icon'     => 'flag',
                    'priority' => 'critical',
                ],
                [
                    'title'    => 'Amara Ochieng registered as Buyer',
                    'message'  => 'New user registration from Nairobi — account is active.',
                    'url'      => '/admin/users',
                    'icon'     => 'users',
                    'priority' => 'info',
                ],
                [
                    'title'    => 'David Kimani applied as Vendor',
                    'message'  => 'Pending verification and KYC document review.',
                    'url'      => '/admin/vendors',
                    'icon'     => 'store',
                    'priority' => 'info',
                ],
                [
                    'title'    => '34 orders processed today',
                    'message'  => 'Platform activity is up 18% compared to yesterday.',
                    'url'      => '/admin/dashboard',
                    'icon'     => 'box',
                    'priority' => 'success',
                ],
            ],

            $role === RoleName::VendorManager => [
                [
                    'title'    => '8 vendor applications pending',
                    'message'  => 'Applications need review — KYC docs submitted by 6 of 8.',
                    'url'      => '/admin/vendors',
                    'icon'     => 'store',
                    'priority' => 'warning',
                ],
                [
                    'title'    => 'GlobalTech Kenya submitted KYC docs',
                    'message'  => 'All required documents uploaded. Ready for approval.',
                    'url'      => '/admin/vendors',
                    'icon'     => 'store',
                    'priority' => 'info',
                ],
                [
                    'title'    => 'Nairobi Gadgets Hub approved',
                    'message'  => 'Vendor account activated and ready to list devices.',
                    'url'      => '/admin/vendor-manager/dashboard',
                    'icon'     => 'store',
                    'priority' => 'success',
                ],
            ],

            $role === RoleName::Verifier => [
                [
                    'title'    => '14 devices in the inspection queue',
                    'message'  => '3 marked high-priority — waiting over 48 hours.',
                    'url'      => '/verifier/queue',
                    'icon'     => 'shield',
                    'priority' => 'warning',
                ],
                [
                    'title'    => 'iPhone 14 Pro Max — priority inspection',
                    'message'  => 'Submitted by Kamau Electronics. IMEI pre-check passed.',
                    'url'      => '/verifier/queue',
                    'icon'     => 'shield',
                    'priority' => 'critical',
                ],
                [
                    'title'    => 'Samsung Galaxy S24 Ultra added to queue',
                    'message'  => 'Submitted 20 minutes ago by Nairobi Gadgets Hub.',
                    'url'      => '/verifier/queue',
                    'icon'     => 'shield',
                    'priority' => 'info',
                ],
                [
                    'title'    => 'MacBook Air M3 — IMEI flagged',
                    'message'  => 'Serial number matched a theft report entry. Review required.',
                    'url'      => '/verifier/queue',
                    'icon'     => 'flag',
                    'priority' => 'critical',
                ],
                [
                    'title'    => '9 devices certified today',
                    'message'  => 'Trust certificates issued and QR codes activated.',
                    'url'      => '/verifier/dashboard',
                    'icon'     => 'shield',
                    'priority' => 'success',
                ],
            ],

            $role === RoleName::CustomerService => [
                [
                    'title'    => '2 disputes flagged as high priority',
                    'message'  => 'Buyer claims non-delivery — escrow hold pending resolution.',
                    'url'      => '/admin/disputes',
                    'icon'     => 'flag',
                    'priority' => 'critical',
                ],
                [
                    'title'    => 'Refund request: ORD-4821',
                    'message'  => 'Buyer disputes device condition. Refund KSh 89,500 awaiting approval.',
                    'url'      => '/admin/disputes',
                    'icon'     => 'box',
                    'priority' => 'warning',
                ],
                [
                    'title'    => 'Dispute DIS-003 resolved',
                    'message'  => 'Buyer and seller reached agreement. Escrow released.',
                    'url'      => '/admin/cs/dashboard',
                    'icon'     => 'package',
                    'priority' => 'success',
                ],
                [
                    'title'    => 'New support message from Faith Wanjiru',
                    'message'  => 'Asking about order tracking for ORD-4899.',
                    'url'      => '/admin/disputes',
                    'icon'     => 'users',
                    'priority' => 'info',
                ],
            ],

            $role === RoleName::ContentManager => [
                [
                    'title'    => '22 products awaiting content review',
                    'message'  => '7 currently in review. 15 have not been started.',
                    'url'      => '/admin/products',
                    'icon'     => 'package',
                    'priority' => 'warning',
                ],
                [
                    'title'    => 'MacBook Air M3 submitted for review',
                    'message'  => 'Listed by GlobalTech Kenya. Specs and images uploaded.',
                    'url'      => '/admin/products',
                    'icon'     => 'package',
                    'priority' => 'info',
                ],
                [
                    'title'    => '6 products approved in the last hour',
                    'message'  => 'All passed content review and are now live on the marketplace.',
                    'url'      => '/admin/content/dashboard',
                    'icon'     => 'layers',
                    'priority' => 'success',
                ],
            ],

            $role === RoleName::Vendor => [
                [
                    'title'    => 'New order: ORD-5001',
                    'message'  => 'iPhone 14 Pro 256GB — KSh 78,000. Awaiting fulfilment.',
                    'url'      => '/vendor/dashboard',
                    'icon'     => 'box',
                    'priority' => 'success',
                ],
                [
                    'title'    => 'Listing verified: Samsung Galaxy S23',
                    'message'  => 'Trust Certificate issued. Your listing is now live.',
                    'url'      => '/vendor/listings',
                    'icon'     => 'shield',
                    'priority' => 'success',
                ],
                [
                    'title'    => 'ORD-4987 marked as delivered',
                    'message'  => 'Buyer confirmed receipt. Escrow release in 3 days.',
                    'url'      => '/vendor/dashboard',
                    'icon'     => 'package',
                    'priority' => 'info',
                ],
                [
                    'title'    => 'Listing rejected: iPad Pro M4',
                    'message'  => 'Verifier flagged IMEI mismatch. Review and resubmit.',
                    'url'      => '/vendor/listings',
                    'icon'     => 'flag',
                    'priority' => 'critical',
                ],
            ],

            $role === RoleName::User => [
                [
                    'title'    => 'Order ORD-4821 delivered',
                    'message'  => 'Your iPhone 14 Pro Max has been delivered. Confirm receipt.',
                    'url'      => '/orders',
                    'icon'     => 'box',
                    'priority' => 'success',
                ],
                [
                    'title'    => 'Device verification complete',
                    'message'  => 'Trust Certificate issued for your Samsung Galaxy S24 order.',
                    'url'      => '/orders',
                    'icon'     => 'shield',
                    'priority' => 'success',
                ],
                [
                    'title'    => 'Wishlist item now available',
                    'message'  => 'MacBook Air M3 is back in stock — verified and ready to buy.',
                    'url'      => '/browse',
                    'icon'     => 'search',
                    'priority' => 'info',
                ],
            ],

            default => [],
        };
    }
}
