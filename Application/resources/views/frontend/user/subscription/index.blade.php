@extends('frontend.user.layouts.dash')
@section('title', lang('My Subscription', 'user'))
@section('content')
    @if (!subscription()->is_canceled)
        <div class="row g-3 mb-4">
            {{-- <div class="col-lg-12">
                <div class="vr__counter__box white h-100">
                    <div class="bx mb-2">
                        
                        @if (!subscription()->is_lifetime)
                            <h3 class="vr__counter__box__title">{{ lang('Subscription Expiry', 'user') }}</h3>
                            <p class="vr__counter__box__number">
                                <span class="{{ subscription()->remining_days < 1 ? 'text-danger' : 'text-success' }}">
                                    {{ subscription()->expired_at }}
                                </span>
                            </p>
                        @else
                            <h3 class="vr__counter__box__title mb-3">{{ lang('Lifetime Subscription', 'user') }}</h3>
                        @endif
                        <span class="vr__counter__box__icon pb-2 pe-3">
                            <i class="far fa-calendar-alt"></i>
                        </span>
                    </div>
                    @if (!subscription()->plan->is_free && !subscription()->is_lifetime)
                        <form class="d-inline me-2"
                            action="{{ route('subscribe', [hashid(subscription()->plan->id), 'renew']) }}" method="POST">
                            @csrf
                            <button class="vr__confirm__action__form btn btn-secondary btn-sm"><i
                                    class="fas fa-sync-alt me-2"></i>{{ lang('Renew Subscription', 'user') }}</button>
                        </form>
                    @endif

                    <a href="{{ $plans > 1 ? route('user.plans') : '#' }}"
                        class="btn btn-blue btn-sm {{ $plans > 1 ? '' : 'disabled' }}"><i
                            class="far fa-gem me-2"></i>{{ lang('Upgrade', 'user') }}</a>
                </div>
            </div> --}}
            <div class="col-lg-12">
                <div class="custom-list card">
                    {{-- <div class="card-header">{{ lang('Subscription details', 'user') }}</div> --}}
                    {{-- <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><strong>{{ lang('Plan Name', 'user') }}</strong></span>
                            <span>{{ subscription()->plan->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><strong>{{ lang('Plan Interval', 'user') }}</strong></span>
                            <span class="text-capitalize">{{ subscription()->plan->interval_name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><strong>{{ lang('Storage Space', 'user') }}</strong></span>
                            <span>{{ subscription()->plan->storage_space }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><strong>{{ lang('Size Per Transfer', 'user') }}</strong></span>
                            <span>{{ subscription()->plan->transfer_size }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><strong>{{ lang('Files duration', 'user') }}</strong></span>
                            <span>{{ subscription()->plan->transfer_interval_days }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><strong>{{ lang('Password protection', 'user') }}</strong></span>
                            <span>{!! subscription()->plan->transfer_password ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' !!}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><strong>{{ lang('Email notification', 'user') }}</strong></span>
                            <span>{!! subscription()->plan->transfer_notify ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' !!}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><strong>{{ lang('Expiry time control', 'user') }}</strong></span>
                            <span>{!! subscription()->plan->transfer_expiry ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' !!}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><strong>{{ lang('Generate transfer links', 'user') }}</strong></span>
                            <span>{!! subscription()->plan->transfer_link ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' !!}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><strong>{{ lang('Advertisements', 'user') }}</strong></span>
                            <span>{!! subscription()->plan->advertisements ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' !!}</span>
                        </li>
                    </ul> --}}
                    @if ($transactions->count() > 0)

                    {{-- PLAN ADDED BY MH --}}
                    <div class="plans plans-dashboard">
                
                     <div class="plans-item active">
                         <div
                             class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-3 g-3 justify-content-between align-items-stretch">
                 
                             <div class="col aos-init aos-animate" data-aos="zoom-out-right" data-aos-duration="1000">
                                 <div
                                     class="position-relative box rounded-3 p-5 pb-7 pt-7 shadow mb-5  rounded d-flex flex-column gap-4  plan  hover:border-gray-700 border-transparent border-2">
                 
                                     {{-- <p class="plan-title">Free</p> --}}
                 
                 
                                     <div class="include-dashboard d-flex flex-column justify-content-between w-100">
                                         <p class="plan-title">Basic</p>
                                         <p class="fw-bold text-body-secondary plan-price">Free
                                         </p>
                                         <p class="mcharge">Monthly Charge</p>         
                                     </div>
                                     <div class="plan-features mb-4">
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon"><i class="fa fa-check"></i></div>
                                             <span>
                                                 <strong>10.00 GB</strong>&nbsp;Storage Space
                                             </span>
                                         </div>
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon"><i class="fa fa-check"></i></div>
                                             <span>
                                                 <strong>2.00 GB</strong>&nbsp;Size per transfer
                                             </span>
                                         </div>
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon"><i class="fa fa-check"></i></div>
                                             <span>
                                                 Files available for&nbsp;<strong>14&nbsp;days</strong>
                                             </span>
                                         </div>
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon"><i class="fa fa-check"></i></div>
                                             <span> Password protection</span>
                                         </div>
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon"><i class="fa fa-check"></i></div>
                                             <span>Email notification</span>
                                         </div>
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon"><i class="fa fa-check"></i></div>
                                             <span>Expiry time control</span>
                                         </div>
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon"><i class="fa fa-check"></i></div>
                                             <span>Generate transfer links</span>
                                         </div>
                                     </div>
                                     <div class="plan-action">
                                         
                                         {{-- <form action="http://127.0.0.1:8000/en/plan/E0jr0z/upgrade" method="POST"><input type="hidden"
                                                 name="_token" value="YE2HAJzR470cfj91aWkSmnPLjzweveC7MTwbu9xT"><button type="submit"
                                                 class="btn btn-outline-secondary w-100 py-2 vr__confirm__action__form">Upgrade
                                                 plan</button></form> --}}
                                                 <a href="{{ $plans > 1 ? route('user.plans') : '#' }}"
                                                     class="btn btn-secondary w-100 py-2 vr__confirm__action__form {{ $plans > 1 ? '' : 'disabled' }}">{{ lang('Upgrade', 'user') }}</a>
                                     </div>
                                 </div>
                             </div>
                 
                             <div class="col aos-init aos-animate" data-aos="zoom-out-right" data-aos-duration="1000">
                                 <div
                                     class="position-relative box rounded-3 p-5 pb-7 pt-7 shadow mb-5  rounded d-flex flex-column gap-4  plan plan-current hover:border-gray-700 border-transparent border-2">
                                     {{-- <div class="plan-badge"><span>Your plan</span></div> --}}
                                     {{-- <p class="plan-title">Starter</p> --}}
                 
                 
                                     <div class="include-dashboard d-flex flex-column justify-content-between w-100">
                                         <p class="plan-title">Starter</p>
                                         <p class="fw-bold text-body-secondary plan-price">$11.00&nbsp;
                                         </p>
                                         <p class="mcharge">Monthly Charge</p>         
                                     </div>
                                     <div class="plan-features mb-4">
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon">
                                                 <i class="fa fa-check"></i>
                                             </div>
                                             <span>
                                                 <strong>150.00 GB</strong>&nbsp;Storage Space
                                             </span>
                                         </div>
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon"><i class="fa fa-check"></i></div>
                                             <span>
                                                 <strong>100.00 GB</strong>&nbsp;Size per transfer
                                             </span>
                                         </div>
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon"><i class="fa fa-check"></i></div>
                                             <span>
                                                 Files available for&nbsp;<strong>180&nbsp;days</strong>
                                             </span>
                                         </div>
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon"><i class="fa fa-check"></i></div>
                                             <span> Password protection</span>
                                         </div>
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon"><i class="fa fa-check"></i></div>
                                             <span>Email notification</span>
                                         </div>
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon"><i class="fa fa-check"></i></div>
                                             <span>Expiry time control</span>
                                         </div>
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon"><i class="fa fa-check"></i></div>
                                             <span>Generate transfer links</span>
                                         </div>
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon"><i class="fa fa-check"></i></div>
                                             <span>No Advertisements</span>
                                         </div>
                                     </div>
                                     <div class="plan-action">
                                         @if (!subscription()->plan->is_free && !subscription()->is_lifetime)
                                             <form class="d-inline me-2"
                                                 action="{{ route('subscribe', [hashid(subscription()->plan->id), 'renew']) }}" method="POST">
                                                 @csrf
                                                 <button class="btn btn-secondary w-100 py-2 vr__confirm__action__form">
                                                     {{ lang('Renew Subscription', 'user') }}
                                                 </button>
                                             </form>
                                             
                                         @endif
                                         {{-- <form action="http://127.0.0.1:8000/en/plan/GMel92/renew" method="POST"><input type="hidden"
                                                 name="_token" value="YE2HAJzR470cfj91aWkSmnPLjzweveC7MTwbu9xT"><button type="submit"
                                                 class="btn btn-primary w-100 py-2 vr__confirm__action__form">Renew plan</button></form> --}}
                                     </div>
                                 </div>
                             </div>
                 
                             <div class="col aos-init aos-animate" data-aos="zoom-out-right" data-aos-duration="1000">
                                 <div
                                     class="position-relative box rounded-3 p-5 pb-7 pt-7 shadow mb-5  rounded d-flex flex-column gap-4  plan plan-featured hover:border-gray-700 border-transparent border-2">
                                     {{-- <div class="plan-badge"><span>Best value</span></div> --}}
                                     {{-- <p class="plan-title">Premium</p> --}}
                 
                 
                                     <div class="include-dashboard d-flex flex-column justify-content-between w-100">
                                         <p class="plan-title">Premium</p>
                                         <p class="fw-bold text-body-secondary plan-price">$28.00
                                         </p>
                                         <p class="mcharge">Monthly Charge</p>         
                                     </div>
                                     <div class="plan-features mb-4">
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon"><i class="fa fa-check"></i></div>
                                             <span>
                                                 <strong>400.00 GB</strong>&nbsp;Storage Space
                                             </span>
                                         </div>
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon"><i class="fa fa-check"></i></div>
                                             <span>
                                                 <strong>100.00 GB</strong>&nbsp;Size per transfer
                                             </span>
                                         </div>
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon"><i class="fa fa-check"></i></div>
                                             <span>
                                                 Files available for&nbsp;<strong>180&nbsp;days</strong>
                                             </span>
                                         </div>
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon"><i class="fa fa-check"></i></div>
                                             <span> Password protection</span>
                                         </div>
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon"><i class="fa fa-check"></i></div>
                                             <span>Email notification</span>
                                         </div>
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon"><i class="fa fa-check"></i></div>
                                             <span>Expiry time control</span>
                                         </div>
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon"><i class="fa fa-check"></i></div>
                                             <span>Generate transfer links</span>
                                         </div>
                                         <div class="plan-feature-item">
                                             <div class="plan-feature-icon"><i class="fa fa-check"></i></div>
                                             <span>No Advertisements</span>
                                         </div>
                                     </div>
                                     <div class="plan-action">
                                         <form action="http://127.0.0.1:8000/en/plan/rMX25D/upgrade" method="POST"><input type="hidden"
                                                 name="_token" value="YE2HAJzR470cfj91aWkSmnPLjzweveC7MTwbu9xT"><button type="submit"
                                                 class="btn btn-secondary w-100 py-2 vr__confirm__action__form">Upgrade plan</button>
                                         </form>
                                     </div>
                                 </div>
                             </div>
                 
                         </div>
                     </div>
                 </div>
                 {{-- END PLAN ADDED BY MH   --}}
                
                 @endif
                </div>
            </div>
            

        </div>
    @else
        <div class="alert bg-danger text-white border-0">
            <i class="far fa-times-circle me-2"></i>
            {{ lang('Your subscription has been canceled, please contact us for more information', 'alerts') }}
        </div>
    @endif






    @if ($transactions->count() > 0)
        <div class="transactions-table">
            <h5 class="fs-5 mb-4">{{ lang('Transactions', 'user') }}</h5>
            <div class="vr__table">
                <table>
                    <thead>
                        <th>{{ lang('Transaction Number', 'user') }}</th>
                        <th class="text-center">{{ lang('Plan (Interval)', 'user') }}</th>
                        <th class="text-center">{{ lang('Plan Price', 'user') }}</th>
                        <th class="text-center">{{ lang('Total', 'user') }}</th>
                        <th class="text-center">{{ lang('Type', 'user') }}</th>
                        <th class="text-center">{{ lang('Status', 'user') }}</th>
                        <th class="text-center">{{ lang('Transaction date', 'user') }}</th>
                        <th class="text-center">{{ lang('Action', 'user') }}</th>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td><a href="{{ route('user.transaction', $transaction->transaction_id) }}"
                                        class="text-dark"><i
                                            class="fas fa-file-invoice-dollar me-2"></i>#{{ $transaction->transaction_id }}</a>
                                </td>
                                <td class="text-center text-capitalize">
                                    {{ $transaction->plan->name }}
                                    @if ($transaction->plan->interval == 0)
                                        ({{ lang('Monthly', 'plans') }})
                                    @elseif($transaction->plan->interval == 1)
                                        ({{ lang('Yearly', 'plans') }})
                                    @elseif($transaction->plan->interval == 2)
                                        ({{ lang('lifetime', 'plans') }})
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ priceSymbol($transaction->details_before_discount->plan_price) }}
                                </td>
                                <td class="text-center">
                                    <strong>{{ priceSymbol($transaction->total_price) }}</strong>
                                </td>
                                <td class="text-center">
                                    @if ($transaction->type == 0)
                                        <strong>{{ lang('Subscribe', 'user') }}</strong>
                                    @elseif($transaction->type == 1)
                                        <strong>{{ lang('Renew', 'user') }}</strong>
                                    @elseif($transaction->type == 2)
                                        <strong>{{ lang('Upgrade', 'user') }}</strong>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($transaction->plan_price != 0)
                                        @if ($transaction->status == 2)
                                            <span class="badge bg-success">{{ lang('Paid', 'user') }}</span>
                                        @elseif($transaction->status == 3)
                                            <span class="badge bg-danger">{{ lang('Canceled', 'user') }}</span>
                                        @endif
                                    @else
                                        @if ($transaction->status == 2)
                                            <span class="badge bg-secondary">{{ lang('Done', 'user') }}</span>
                                        @elseif($transaction->status == 3)
                                            <span class="badge bg-danger">{{ lang('Canceled', 'user') }}</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center">{{ vDate($transaction->created_at) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('user.transaction', $transaction->transaction_id) }}"
                                        class="btn btn-blue btn-sm">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $transactions->links() }}
        </div>
    @endif

    <br>
    <hr>



@endsection
