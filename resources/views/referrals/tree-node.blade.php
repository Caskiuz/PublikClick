<li>
    <div class="tree-node level-{{ $node['level'] }} {{ $node['is_active'] ? '' : 'inactive' }}">
        <div class="font-semibold text-sm">{{ $node['name'] }}</div>
        <div class="text-xs text-gray-600 mt-1">{{ $node['email'] }}</div>
        <div class="text-xs text-gray-500 mt-1">{{ $node['package'] }}</div>
        <div class="mt-2">
            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full {{ $node['is_active'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                {{ $node['rank'] }}
            </span>
        </div>
    </div>
    @if(count($node['children']) > 0)
        <ul>
            @foreach($node['children'] as $child)
                @include('referrals.tree-node', ['node' => $child])
            @endforeach
        </ul>
    @endif
</li>
