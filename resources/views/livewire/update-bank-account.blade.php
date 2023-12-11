<div>
    <div class="flex flex-row w-full justify-center items-center mt-auto">
        <div
            class="w-2/5 max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <form wire:submit="update({{ $id }})">
                <div class="mb-6">
                    <label for="accountName"
                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('Account name')}}</label>
                    <input type="text" id="accountName"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           wire:model="accountName">
                    @error('accountName') <span class="error">{{ $message }}</span> @enderror

                </div>
                <div class="mb-6">
                    <label for="accountApiToken"
                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('Account API token')}}</label>
                    <input type="text" wire:model="accountApiToken" id="accountApiToken"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                           required>

                    @error('accountApiToken') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="mb-6">
                    <x-currency-select wire:model="accountCurrency"/>
                    @error('accountCurrency') <span class="error">{{ $message }}</span> @enderror
                </div>
                <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    {{__('Update')}}
                </button>
            </form>
        </div>
    </div>
</div>
