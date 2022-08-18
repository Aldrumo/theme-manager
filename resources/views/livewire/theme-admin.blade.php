<div>
    <div class="block w-full">
        <x-Admin::flash></x-Admin::flash>
    </div>

    <table class="min-w-full">
        <thead>
        <tr>
            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Theme Name') }}</th>
            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Installed') }}</th>
            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Active Theme') }}</th>
        </tr>
        </thead>

        <tbody class="bg-white">
            @foreach ($themes as $theme)
                <tr>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        <div class="leading-5 font-medium text-gray-900">
                            {{ $theme->packageName() }}
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium leading-5 bg-green-100 text-green-800 hover:bg-green-800 hover:text-green-100">>Installed</span>
                        <span>Active</span>
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        @if ($theme->isActive())
                            <button class="p-2 bg-red-600 rounded-md text-white font-medium
                                tracking-wide hover:bg-red-700 disabled:opacity-50 ..." disabled>
                                {{ __('Uninstall Theme') }}
                            </button>
                        @elseif ($theme->isInstalled())
                            <button class="p-2 bg-red-600 rounded-md text-white font-medium
                                tracking-wide hover:bg-red-700">
                                {{ __('Uninstall Theme') }}
                            </button>
                        @else
                            <button class="p-2 bg-green-600 rounded-md text-white font-medium
                                tracking-wide hover:bg-green-700">
                                {{ __('Install Theme') }}
                            </button>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        @if ($theme->isInstalled())
                            @if ($theme->isActive())
                                <button class="p-2 bg-green-600 rounded-md text-white font-medium
                                tracking-wide hover:bg-green-700 disabled:opacity-50 ..." disabled>
                                    {{ __('Active Theme') }}
                                </button>
                            @else
                                <button class="p-2 bg-green-600 rounded-md text-white font-medium
                                tracking-wide hover:bg-green-700">
                                    {{ __('Activate') }}
                                </button>
                            @endif
                        @else
                            <button class="p-2 bg-green-600 rounded-md text-white font-medium
                                tracking-wide hover:bg-green-700">
                                {{ __('Install Theme') }}
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
