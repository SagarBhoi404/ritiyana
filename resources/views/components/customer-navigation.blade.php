<div class="bg-white rounded-lg shadow-sm mb-8">
    <div class="px-6 py-4">
        <nav class="flex flex-wrap gap-6">
            @foreach($menuItems() as $item)
                <a href="{{ route($item['route']) }}" 
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors
                          {{ $item['active'] 
                              ? 'text-vibrant-pink bg-pink-50' 
                              : 'text-gray-600 hover:text-vibrant-pink hover:bg-pink-50' }}">
                    <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4 mr-2"></i>
                    {{ $item['name'] }}
                </a>
            @endforeach
        </nav>
    </div>
</div>
