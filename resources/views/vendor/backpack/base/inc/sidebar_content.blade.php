{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-question"></i> Users</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('artist') }}"><i class="nav-icon la la-question"></i> Artists</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('category') }}"><i class="nav-icon la la-question"></i> Categories</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('feat') }}"><i class="nav-icon la la-question"></i> Feats</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('like') }}"><i class="nav-icon la la-question"></i> Likes</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('music') }}"><i class="nav-icon la la-question"></i> Music</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('visit') }}"><i class="nav-icon la la-question"></i> Visits</a></li>