<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Config;

new class extends Component {
    public $hasNewNotifications = false;

    #[On('push-notification')]
    #[Computed]
    public function hasNewNotifications()
    {
        $this->hasNewNotifications = $this->notices() ? true : false;
    }

    #[Computed]
    public function notices()
    {
        $notices = Auth::user()->unreadNotifications ?? null;
        return $notices;
    }
};

?>

<div>
    <div class="hs-dropdown relative inline-flex [--placement:bottom-right] [--auto-close:inside]" x-data="{ message: '', showIndicator: $wire.entangle('hasNewNotifications') }"
        x-init="navigator.serviceWorker.addEventListener('message', function(event) {
        
            // listen incoming messages from background
            if (event.data && event.data.type === 'firebase-message') {
                message = event.data.payload.notification.body;
                console.log('Pesan dari service worker:', message);
        
                showIndicator = true;
                $wire.$refresh();
        
                // dispatch event
                $dispatch('push-notification');
            }
        });">

        <button type="button" id="hs-notification-dropdown" x-cloak @click="showIndicator=false"
            class="inline-flex flex-shrink-0 bg-gray-50 text-gray-600 justify-center items-center gap-2 h-[2.375rem] w-[2.375rem] rounded-full font-medium align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:ring-offset-white transition-all text-xs dark:bg-gray-800 dark:hover:bg-slate-800 dark:text-gray-400 dark:hover:text-white dark:focus:ring-gray-700 dark:focus:ring-offset-gray-800">
            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                viewBox="0 0 16 16">
                <path
                    d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z" />
            </svg>

            {{-- show indicator if has new message --}}
            @if ($this->notices->count() > 0)
                <span class="absolute top-0 flex end-0 size-3" x-show="showIndicator">
                    <span
                        class="absolute inline-flex bg-red-400 rounded-full opacity-75 animate-ping size-full dark:bg-red-600"></span>
                    <span class="relative inline-flex bg-red-500 rounded-full size-3"></span>
                </span>
            @endif

        </button>

        <div class="hs-dropdown-menu transition-[opacity,margin] duration h-3/4 divide-y hs-dropdown-open:opacity-100 opacity-0 hidden min-w-[23rem] max-w-[30rem] overflow-hidden bg-white shadow-xl rounded-xl dark:bg-gray-800 dark:border-gray-700 border border-gray-300 antialiased"
            aria-labelledby="hs-notification-dropdown">
            <div class="px-4 py-3">
                <p class="text-base antialiased font-semibold text-gray-700 dark:text-neutral-400">Notifications</p>
            </div>
            <article class="px-4 py-4 overflow-y-auto h-5/6 scrollbar-thin scroll-smooth scrollbar-thumb-gray-300">
                @if ($hasNewNotifications)
                    @foreach ($this->notices as $notification)
                        <a href="/" class="relative flex px-4 group gap-x-5 hover:bg-gray-100 hover:rounded-lg">

                            <!-- Icon -->
                            <div
                                class="relative mt-2 group-last:after:hidden after:absolute after:top-8 after:bottom-2 after:start-3 after:w-px after:-translate-x-[0.5px] after:bg-gray-200 dark:after:bg-neutral-700">
                                <div class="relative z-10 flex items-center justify-center size-6">
                                    <svg class="text-gray-600 shrink-0 size-6 dark:text-neutral-400" width="32"
                                        height="32" viewBox="0 0 32 32" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.7438 0.940745C6.84695 1.30308 2.6841 1.63631 2.48837 1.67533C1.9396 1.77319 1.44038 2.14544 1.20563 2.63537L1 3.06646L1.01982 13.3407L1.04893 23.615L1.36234 24.2517C1.53886 24.6042 2.73365 26.2499 4.0362 27.9439C6.61221 31.2836 6.79802 31.47 7.77726 31.5679C8.06156 31.597 10.1966 31.4991 12.5081 31.3622C14.8295 31.2154 18.5508 30.99 20.7842 30.863C30.3233 30.2839 29.8334 30.3328 30.3815 29.8627C31.0672 29.2947 31.0183 30.2251 31.0474 17.7377C31.0672 7.15003 31.0573 6.45509 30.9006 6.13177C30.7148 5.76943 30.3815 5.51487 26.0329 2.45885C23.1243 0.421704 22.9186 0.313932 21.6155 0.294111C21.0772 0.274911 16.6307 0.568497 11.7438 0.940745ZM22.752 2.28232C23.1633 2.46814 26.1704 4.56412 26.6108 4.9661C26.7284 5.08378 26.7675 5.18164 26.7086 5.24048C26.5717 5.35817 7.96245 6.465 7.42421 6.38634C7.17956 6.34732 6.81722 6.20052 6.61159 6.06302C5.75932 5.48514 3.64413 3.75149 3.64413 3.62452C3.64413 3.29129 3.57538 3.29129 11.8714 2.69421C13.4582 2.58644 16.0633 2.39071 17.6502 2.26312C21.0871 1.98874 22.1159 1.99865 22.752 2.28232ZM28.6677 7.63996C28.8046 7.77685 28.9223 8.04132 28.9613 8.29589C28.9904 8.53125 29.0102 12.9189 28.9904 18.0313C28.9613 26.8067 28.9514 27.3555 28.7848 27.61C28.6869 27.7667 28.4912 27.9333 28.3438 27.9823C27.9331 28.1489 8.43318 29.2557 8.03183 29.138C7.84601 29.0891 7.59083 28.9324 7.45394 28.7955L7.21858 28.541L7.18947 19.0799C7.16965 12.4395 7.18947 9.5012 7.26813 9.23672C7.32697 9.041 7.47376 8.80564 7.60136 8.72759C7.77788 8.60991 8.93364 8.51205 12.9101 8.2773C15.7016 8.1206 20.0206 7.85613 22.4987 7.70933C28.3933 7.34638 28.3741 7.34638 28.6677 7.63996Z"
                                            class="fill-black dark:fill-neutral-200" fill="currentColor"></path>
                                        <path
                                            d="M23.4277 10.8818C22.3698 10.9506 21.4296 11.0484 21.3218 11.1073C20.9985 11.2739 20.8028 11.5483 20.7638 11.8617C20.7347 12.185 20.8325 12.224 21.8898 12.3516L22.35 12.4104V16.5925C22.35 19.0799 22.311 20.7256 22.2621 20.6767C22.2131 20.6178 20.8226 18.5027 19.167 15.9756C17.512 13.4392 16.1407 11.3525 16.1209 11.3333C16.1011 11.3135 15.024 11.3724 13.7313 11.4609C12.1445 11.5687 11.273 11.6666 11.0965 11.7644C10.8122 11.9112 10.4988 12.4303 10.4988 12.7734C10.4988 12.979 10.871 13.0868 11.6545 13.0868H12.0658V25.1139L11.4 25.3196C10.8809 25.4763 10.7044 25.5741 10.6165 25.7698C10.4598 26.1031 10.4697 26.4066 10.6264 26.4066C10.6852 26.4066 11.792 26.3378 13.0649 26.2598C15.582 26.113 15.8657 26.0442 16.1302 25.5252C16.2088 25.3685 16.277 25.2019 16.277 25.1529C16.277 25.1139 15.9345 24.9962 15.5226 24.8984C15.1014 24.8005 14.6802 24.7027 14.5923 24.6828C14.4257 24.6339 14.4157 24.3304 14.4157 20.1186V15.6033L17.3931 20.2753C20.5173 25.1721 20.9093 25.7308 21.3893 25.9755C21.987 26.2889 23.5051 26.0733 24.2688 25.5741L24.5042 25.4273L24.524 18.7479L24.5531 12.0586L25.0722 11.9608C25.6891 11.8431 25.9734 11.5594 25.9734 11.0695C25.9734 10.7561 25.9536 10.7362 25.66 10.7462C25.4847 10.7542 24.4757 10.813 23.4277 10.8818Z"
                                            class="fill-black dark:fill-neutral-200" fill="currentColor"></path>
                                    </svg>
                                </div>
                            </div>
                            <!-- End Icon -->

                            <!-- Right Content -->
                            <div class="pb-8 grow group-last:pb-0 text-pretty">
                                <h3 class="mt-2 mb-1 text-xs text-gray-600 dark:text-neutral-400">
                                    {{ date('d M Y H:i:s', strtotime($notification->created_at)) }}
                                </h3>
                                @foreach ($notification->data as $data)
                                    <p class="text-sm font-semibold text-gray-800 dark:text-neutral-200">
                                        {{ $data['title'] }}
                                    </p>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-neutral-400">
                                        {{ $data['message'] }}
                                    </p>
                                @endforeach
                            </div>
                            <!-- End Right Content -->

                        </a>
                    @endforeach
                @else
                    <div class="relative flex group gap-x-5">
                        <div class="flex items-center justify-center px-6 pb-8 grow group-last:pb-0">
                            <h3 class="mb-1 text-sm text-gray-400 dark:text-neutral-400">
                                You don't have new notifications
                            </h3>
                        </div>
                    </div>
                @endif
            </article>

            <div class="flex items-center px-4 pt-3 text-center justify-evenly gap-x-4">
                <button type="button"
                    class="flex items-center justify-center w-full py-2 text-xs font-medium text-center text-gray-500 transition duration-200 border border-gray-300 rounded-md gap-x-2 hover:border-gray-600 hover:text-gray-800 focus:outline-none focus:border-gray-800 focus:text-gray-800 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-400 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hover:border-neutral-300">
                    Mark all as read
                </button>
                <button type="button"
                    class="flex items-center justify-center w-full py-2 text-xs font-medium text-center text-gray-500 transition duration-200 border border-gray-300 rounded-md gap-x-2 hover:border-gray-600 hover:text-gray-800 focus:outline-none focus:border-gray-800 focus:text-gray-800 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-400 dark:text-neutral-400 dark:hover:text-neutral-300 dark:hover:border-neutral-300">
                    Go to Notifications
                </button>
                {{-- <a href="" class="text-sm text-gray-600 dark:text-white">go to notification center</a> --}}
            </div>
        </div>
    </div>

</div>
