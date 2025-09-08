@props(['isAdmin' => false])

<thead class="bg-gray-800">
    <tr>
        <th class="px-6 py-3 text-left text-xs font-medium text-white font-bold uppercase tracking-wider">
            Date Created
        </th>
        @if($isAdmin)
            <th class="px-6 py-3 text-left text-xs font-medium text-white font-bold uppercase tracking-wider">
                Employee
            </th>
        @endif
        <th class="px-6 py-3 text-left text-xs font-medium text-white font-bold uppercase tracking-wider">
            Destination
        </th>
        <th class="px-6 py-3 text-left text-xs font-medium text-white font-bold uppercase tracking-wider">
            Purpose
        </th>
        <th class="px-6 py-3 text-center text-xs font-medium text-white font-bold uppercase tracking-wider">
            Departure Date
        </th>
        <th class="px-6 py-3 text-center text-xs font-medium text-white font-bold uppercase tracking-wider">
            Arrival Date
        </th>
        <th class="px-6 py-3 text-center text-xs font-medium text-white font-bold uppercase tracking-wider">
            Status
        </th>
        <th class="px-6 py-3 text-center text-xs font-medium text-white font-bold uppercase tracking-wider">
            Actions
        </th>
    </tr>
</thead>
