<?php

namespace App\Services\AllServices;

use App\Mail\SendMail;
use App\Models\Subscription;
use App\Models\Vendor;
use Illuminate\Support\Facades\Mail;

class VendorEmails
{
    public static function day_after_signup()
    {
        // dd(now()->startOfDay()->subDay()->toDateTimeString(),now()->startOfDay()->toDateTimeString());
        $vendors = Vendor::where('created_at', '>=', now()->startOfDay()->subDay()->toDateTimeString())->where('created_at', '<', now()->startOfDay()->toDateTimeString())
            ->get();

        // print_r("\nsending  after 24 hrs emails to " . $vendors->count() . " vendors\n");

        foreach ($vendors as $key => $vendor) {
            Mail::to($vendor->email)
                ->queue(new SendMail(
                    $vendor->company_name,
                    'Tips on getting the most out of your account on Dinesurf',

                    "
                    <h3>Get discovered by diners</h3>

                    <p>Most diners check a menu, price, and restaurant photos before choosing to dine at a
                    restaurant. Ensure yours is available on your listing page.</p>

                    <p>Diners are likely to go to a restaurant that provides adequate info about themselves. A
                    concise description of what is unique about your restaurant, and cuisine encourages
                    diners to make a reservation. Ensure yours is available on your listing page.</p>

                    <div class='reservation-div'> <a href='".route('vendors.profile.show')."' class='btn reservation-btn'>Update Now</a> </div>
                "
                ));
        }
    }

    public static function two_days_after_signup()
    {
        // dd(now()->startOfDay()->subDay()->toDateTimeString(),now()->startOfDay()->toDateTimeString());
        $vendors = Vendor::where('created_at', '>=', now()->startOfDay()->subDays(2)->toDateTimeString())->where('created_at', '<', now()->startOfDay()->toDateTimeString())
            ->get();

        print_r("\nsending  'after 2 days emails' to ".$vendors->count()." vendors\n");

        foreach ($vendors as $key => $vendor) {
            Mail::to($vendor->email)
                ->queue(new SendMail(
                    $vendor->company_name,
                    'Your menu management system',

                    "
                
                    <p>With the power of our menu management tool, you can update, upsell and keep
                    customers up to date in minutes. Have a public link that hosts your live-sync menu
                    and is shareable anywhere on the internet. Create menu, categories, and items now
                    from your account at Menu.</p>

                    <div class='reservation-div'> <a href='".route('vendors.menus')."' class='btn reservation-btn'>Check it Now</a> </div>
                "
                ));
        }
    }

    public static function after_first_reservation($vendor)
    {
        // print_r("\nsending 'after first reservation email' to vendor_id: " . $vendor->id . " \n");

        Mail::to($vendor->email)
            ->queue(new SendMail(
                $vendor->company_name,
                'Your dashboard',
                "
                <h3>Your dashboard</h3>
                  <p>With your dashboard, you can see your restaurant's performance on a daily to monthly
                  basis. You can use this data to improve operations at your restaurant and upsell menu
                  items. You can see;</p>
                  <p>
                  <ul>
                  <li>Daily reservations</li>
                  <li>Total reservations</li>
                  <li>Upcoming reservations</li>
                  <li>Number of guests seated</li>
                  <li>Estimated revenue</li>
                  <li>Number of hours saved from automated follow-ups</li>
                  <li>Number of people viewing your page</li>
                  </ul>
                  </p>

                  <div class='reservation-div' style='justify-content:left !important'> <a href='".route('vendors.dashboard')."' class='btn reservation-btn'>Check it Now</a> </div>

                  <h3  style='margin-top:40px'>Your Guestbook</h3>
                  <p>Diners are likely to revisit or refer your restaurant when you show care. Take advantage
                  of guest data in your guestbook. Send messages to guests via SMS or email on special
                  days or follow-ups after dining at your restaurant.</p>

                  <div class='reservation-div' style='justify-content:left !important'> <a href='".route('vendors.guests')."' class='btn reservation-btn'>Check it Now</a> </div>
             
                  <h3 style='margin-top:40px'>How to set admin roles</h3>
                  <p>You can set admin roles for security and manage human errors or absenteeism. You
                  can add more than one staff to your account and assign them roles. Add and review
                  admins at Admin roles now.</p>

                  <div class='reservation-div'  style='justify-content:left !important'> <a href='".route('teams.show', ['team' => $vendor->id])."' class='btn reservation-btn'>Check it Now</a> </div>
                  "
            ));
    }

    public static function seven_days_after_expiration()
    {
        $vendor_ids = Subscription::where('plan_end', '>=', now()->startOfDay()->addDays(7)->toDateTimeString())->where('plan_end', '<', now()->endOfDay()->addDays(7)->toDateTimeString())
            ->pluck('vendor_id');

        // dd(now()->startOfDay()->subDay()->toDateTimeString(),now()->startOfDay()->toDateTimeString());
        $vendors = Vendor::whereIn('id', $vendor_ids)
            ->get();

        // print_r("\nsending  'seven_days_after_expiration' to " . $vendors->count() . " vendors\n");

        foreach ($vendors as $key => $vendor) {
            Mail::to($vendor->email)
                ->queue(new SendMail(
                    $vendor->company_name,
                    'You have 7 more days to access Dinesurf’s Paid features',

                    '
                
                    <p>Thank you for considering Dinesurf as your restaurant management system. We
                    understand that you have 7 days left in your trial and we want to make sure that you are
                    aware of the benefits of upgrading to a paid account.</p>

                    <p>During your current plan you had '.$vendor->reservations->count().' reservations, when you were Discovered alot of
                    times and you seated '.$vendor->guests->count()." guests</p>

                    <p>We also automated the process of reservation and guest management helping you
                    collect guest data, store it and follow up on your behalf.</p>
                    
                    <p>
                    <ul>
                    <li>Number of guest data captured</li>
                    <li>X amount of automated follow up emails and sms</li>
                    <li>Which saved you x amount of hours</li>
                    </ul>
                    </p>

                    <p>
                    If you do not upgrade by the end of your trial, your account will be automatically
                    deactivated and you will lose access to all of your data and records (157 Guest
                    contacts, including birthdays)
                                        </p>

                                        <p>
                                        Upgrading to a full subscription will give you access to all of the features of Dinesurf
                    including:
                    </p>

                    <p>
                    <ul>
                    <li>Customizable menus</li>
                    <li>Reservations management</li>
                    <li>Marketing tools and support</li>
                    </ul>
                    </p>

                    <p>You have until the end of your trial to upgrade and receive a 10% discount on your
                    subscription.</p>

                    <p>If you need help upgrading or have any questions, please contact us at
                   <a href='mailto:productsupport@dinesurf.com'>productsupport@dinesurf.com</a>  and we will be happy to assist you.</p>

                    <div class='reservation-div'> <a href='".route('vendors.billing')."' class='btn reservation-btn'>Subscribe Now</a> </div>
                "
                ));
        }
    }

    public static function after_subscription($vendor)
    {
        // print_r("\nsending 'after_subscription email' to vendor_id: ".$vendor->id." \n");

        $sub = Subscription::where('vendor_id', $vendor->id)->latest()->first();

        Mail::to($vendor->email)
            ->queue(new SendMail(
                $vendor->company_name,
                'Thank you for subscribing to our '.$sub->plan->name.' plan.',
                "

                  <p>Following your subscription, You will continue to enjoy automated guest and reservation
                  management that allows you to capture customer data, understand, and retain
                  customers with ease.</p>
                  <p>On this plan;</p>
                  
                  <p>
                  <ul>
                  <li>Get Analytics on your business performance</li>
                  <li>Update your menu in minutes</li>
                  <li>Manage your reservations with no extra expertise needed</li>
                  <li>Improve customer loyalty with personalized messages to your guests</li>
                  <li>Get reviews from guests from different platforms to keep delivering excellent service</li>
                  <li>Set admin roles for security and minimize human errors</li>
                  </ul>
                  </p>

                  <p>In addition to the above, you will enjoy marketing support from us by promoting your
                  restaurants on our platform and a dedicated customer success team member to help
                  with any issues you may have during this period.</p>

                  <p>Please contact us at productsupport@gmail.com for any further assistance you may
                  need or call <a href='tel:+16462043895'>+1 (646) 204-3895</a> </p>

                 
                  "
            ));
    }
}

return new VendorEmails;
