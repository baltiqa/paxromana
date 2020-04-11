@extends('panel::layouts.master')

@section('content')
<div class="dashboard-ecommerce">
    <div class="container-fluid dashboard-content ">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title">Dashboard Pax Romana </h2>
                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/" class="breadcrumb-link">Dashboard</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="ecommerce-widget">
            <div class="row">
                <div style="text-align: center; display: flex; margin: 0 auto;" class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title border-bottom pb-2">Quote of the day</h3>
                            <p class="card-text"><q>There is nothing impossible to him who will try.</q><br>- Alexander The Great</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <h5 class="card-header">Pax Romana</h5>
                        <div class="card-body">
                            <p>Welcome to the Romana Panel.<br><br>
                                First of all, I am very thankfull for the commitment of the moderators.<br>
                                We will grow to the number 1# DNM and that can't be done without your help!<br> If you
                                have
                                any suggestion or feedbacks just sent a message via the note or via the staff
                                chat.<br><br>
                                Kind Regards,
                                <br>Augustus </p>
                        </div>
                    </div>
                </div>
               
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <h5 class="card-header">Update Pax Romana</h5>
                        <div class="card-body">
                        <textarea class="form-control" rows="20" readonly="readonly" style="width: 100%; cursor: default">
Date: 12-04-2020 / 07:21
 Pax Romana Website updates:
- We improved the speed of our servers which makes our website incredible faster.
- We improved speed of the Deposit/Withdrawing of BTC/LTC/XMR.
- We added 6 new languages to our website.
- You can invite 1 friend to your conversation , so you can talk with 3 people in once.
- We added more tutorials how to use safely Multisig 2/3 at Pax Romana
- We have added new Market feedbacks as Grey Market, Wall Street Market, Tochka Market and Dark Market to our system.

For having your feedbacks to be imported here at Pax Romana, please create a ticket on Pax Romana and our team will take care of you.

Upcoming update:
- Jabber Notification ( For Moderators and Users)
- Staff Chat
- Minimum Bitcoin Deposit
-  New FE system (soon) 

Pax Romana Admin * Updates
- There is a hidden link to the admin panel if you go to your own profile and click on your name, you will be redirected to a admin panel. 
- Head moderators are now allowed to make somebody moderator, edit permissions(disable disputes or reports), can enable FE.
* If you make a user vendor, the user will get a notification that he or she is a vendor.
* If you enable the FE right, the user will get a notification that he or she got the FE rights.
- Moderators are now allowed to make users to vendor.
- Romana Staff can now edit the Register date of the user.
- Romana Staff can now edit the display name of the user ( shows only in Profiles)
- Romana Staff can see on editting a user the PGP key directly rather visiting the profiles.
- Tickets & Reports & Disputes are instead of 'Moderator ' as sender but the Moderator Username.
* By the dispute, if you have made a decision on the dispute, the seller or the buyer will get notified about the dispute. Also there will be a autoreply of who won the dispute. 
- You can see who has last responded on a ticket or dispute.
- You can see who closed last a ticket or dispute.
- You can while seeing a ticket or dispute, have a shortcut to the user profile.

- Import feedbacks has been better improved, all the feedbacks of the markets are now generalized expect Dream Market. 
 * Tochka,WallStreet,Dark,Grey Market has been added to the feedbacks.

                        </textarea>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                    @if(Session::has('message'))
                    <div class="p-3 mb-2 bg-success text-white">{{ Session::get('message') }}</div>
                    @endif
                        <h5 class="card-header">Give your idea or suggestion</h5>
                        <div class="card-body">
                        <p>Give your idea or suggestion on how Pax Romana should run. Put your name as well!</p>
                        <form action="{{route('panel.submit.idea')}}" method="post">
                        {{ csrf_field() }}
                        <div class="input-group-append">
                        <textarea class="form-control" name="romanaideas" rows="20" style="width: 100%; cursor: default">
                        {{ setting('ideas') }}
                        </textarea>
                        <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                          </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop
