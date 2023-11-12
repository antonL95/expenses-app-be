<div>
    <div class="xl:container xl:mx-auto">
        <div class="flex flex-wrap justify-center w-full">
            @if($accounts !== null)
                @foreach($accounts as $account)
                    <div wire:key="{{$account->id}}" class="w-1/3">
                        <div
                            class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <div class="flex justify-end px-4 pt-4">
                                <!-- Dropdown menu -->
                                <x-dropdown>

                                    <x-slot:trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                class="inline-block text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-1.5"
                                                type="button">
                                                <span class="sr-only">Open dropdown</span>
                                                <svg class="w-5 h-5" aria-hidden="true"
                                                     xmlns="http://www.w3.org/2000/svg"
                                                     fill="currentColor" viewBox="0 0 16 3">
                                                    <path
                                                        d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z"/>
                                                </svg>
                                            </button>
                                        </span>
                                    </x-slot:trigger>
                                    <x-slot:content>
                                        <x-dropdown-link href="{{route('update-bank-account', [$account->id])}}"
                                                         wire:navigate>
                                            {{ __('Update') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link wire:click="deleteBankAccount({{$account->id}})" class="cursor-pointer">
                                            {{ __('Delete') }}
                                        </x-dropdown-link>
                                    </x-slot:content>
                                </x-dropdown>
                            </div>
                            <div class="flex flex-col items-center pb-10">
                                <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $account->accountName }}</h5>
                                <span
                                    class="text-sm text-gray-500 dark:text-gray-400">{{ $account->accountCurrency }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
            <div class="flex flex-row justify-center items-center">

                <a href="{{route('add-bank-account')}}" wire:navigate class="px-3 py-2 text-sm font-medium text-center inline-flex items-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
