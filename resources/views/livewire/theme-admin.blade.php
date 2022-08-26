<div>
    <div class="block w-full">
        <x-Admin::flash></x-Admin::flash>
    </div>

    <table class="min-w-full">
        <thead>
        <tr>
            <th class="w-5/6 px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Theme Name') }}</th>
            <th class="w-1/6 px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">&nbsp;</th>
        </tr>
        </thead>

        <tbody class="bg-white">
            @foreach ($themes as $theme)
                <tr>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        <div class="leading-5 font-medium text-gray-900 mb-1">
                            {{ $theme->packageName() }}
                        </div>
                        @if ($theme->isInstalled())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium leading-5 bg-green-100 text-green-800 hover:bg-green-800 hover:text-green-100">
                                {{ __('Installed') }}
                            </span>
                        @endif

                        @if ($theme->isActive())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium leading-5 bg-red-100 text-red-800 hover:bg-red-800 hover:text-red-100">
                                {{ __('Active') }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                        @if ($theme->isActive())
                            <button class="p-2 bg-red-600 rounded-md text-white text-sm
                            tracking-wide hover:bg-red-700 disabled:opacity-50 ..." disabled>
                                {{ __('Uninstall') }}
                            </button>
                        @elseif ($theme->isInstalled())
                            <button class="p-2 bg-red-600 rounded-md text-white text-sm
                            tracking-wide hover:bg-red-700" wire:click="uninstallTheme('{{$theme->packageName()}}')">
                                {{ __('Uninstall') }}
                            </button>

                            <button class="p-2 bg-green-600 rounded-md text-white text-sm
                            tracking-wide hover:bg-green-700" wire:click="activateTheme('{{$theme->packageName()}}')">
                                {{ __('Activate') }}
                            </button>
                        @else
                            <button class="p-2 bg-green-600 rounded-md text-white text-sm
                            tracking-wide hover:bg-green-700" wire:click="installTheme('{{$theme->packageName()}}')">
                                {{ __('Install') }}
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

