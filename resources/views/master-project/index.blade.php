<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Project') }}
        </h2>
        <div class="my-6" x-data="{ open: false }">
            <div class="inline-flex justify-center gap-10 cursor-pointer border bg-gray-50 rounded-sm p-1 px-2" @click="open = ! open">
                <div>Filter</div>
                <div><i class="fa-solid fa-chevron-down" :class="{ 'fa-chevron-up': open, 'fa-chevron-down': !open }"></i></div>
            </div>
            <fieldset class="border rounded-md p-2 mt-1 hidden" :class="{ 'block': open, 'hidden': !open }">
                <form action="{{ route('master.project.index') }}">
                    <div class="flex mt-2 gap-2">
                        <div class="mb-6 w-1/2 md:w-1/3">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                            <input type="text" id="name" name="name"
                                value="{{ request()->query()['name'] ?? '' }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 w-full focus:border-blue-500 block p-2.5"
                                placeholder="Name...">
                        </div>
                    </div>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Submit
                    </button>
                    <a href="{{ route('master.project.index') }}"
                        class="inline-block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-min px-5 py-2.5 text-center">
                        Reset
                    </a>
                </form>
            </fieldset>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="text-right">
                <a href="{{ route('master.project.create') }}" class="inline-block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-auto px-5 py-2.5 text-center">
                    Create New
                </a>
            </div>

            @if (count($masterProjects))
                <div class="relative overflow-x-auto shadow-md rounded-lg mt-4">
                    @php
                        $sort_direction = request()->query()['sort_direction'] ?? '';
                        $sort_by = request()->query()['sort_by'] ?? '';

                        $get_params = [];

                        if ($sort_direction === 'asc' || $sort_direction === '') {
                            $get_params['sort_direction'] = 'desc';
                            $sort_caret = 'fa-sort-up';
                        } elseif ($sort_direction === 'desc') {
                            $get_params['sort_direction'] = 'asc';
                            $sort_caret = 'fa-sort-down';
                        }
                    @endphp
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    <a
                                        href="{{ route('master.project.index', array_merge(request()->query(), ['sort_by' => 'id'], $get_params)) }}">
                                        <span>Id</span>
                                        <i class="fa-solid {{ $sort_by == 'id' ? $sort_caret : 'fa-sort' }} ml-2"></i>
                                    </a>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <a
                                        href="{{ route('master.project.index', array_merge(request()->query(), ['sort_by' => 'name'], $get_params)) }}">
                                        <span>Name</span>
                                        <i class="fa-solid {{ $sort_by == 'name' ? $sort_caret : 'fa-sort' }} ml-2"></i>
                                    </a>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <a
                                        href="{{ route('master.project.index', array_merge(request()->query(), ['sort_by' => 'daily_period_from'], $get_params)) }}">
                                        <span>Reporting Period From</span>
                                        <i class="fa-solid {{ $sort_by == 'daily_period_from' ? $sort_caret : 'fa-sort' }} ml-2"></i>
                                    </a>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <a
                                        href="{{ route('master.project.index', array_merge(request()->query(), ['sort_by' => 'daily_period_to'], $get_params)) }}">
                                        <span>Reporting Period To</span>
                                        <i class="fa-solid {{ $sort_by == 'daily_period_to' ? $sort_caret : 'fa-sort' }} ml-2"></i>
                                    </a>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($masterProjects as $masterProject)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $masterProject->id }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ $masterProject->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ Carbon\Carbon::createFromFormat('H:i:s', $masterProject->daily_period_from)->tz('Asia/Kuala_Lumpur')->format('g:i A') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ Carbon\Carbon::createFromFormat('H:i:s', $masterProject->daily_period_to)->tz('Asia/Kuala_Lumpur')->format('g:i A') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('master.project.edit', ['masterProject' => $masterProject]) }}"
                                            class="font-medium text-blue-600 hover:underline">Edit</a>
                                        <form
                                            action="{{ route('master.project.destroy', ['masterProject' => $masterProject]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="font-medium text-red-600 hover:underline"
                                                onclick="return confirm('Are you sure you want to delete?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Pagination links -->
                <div class="mt-4">
                    {{ $masterProjects->appends($_GET)->links() }}
                </div>
            @else
                <div class="bg-white p-7 mt-4 shadow-md rounded-lg">
                    No data yet.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
