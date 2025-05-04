<a href="{{ route($route, ['sort' => $column, 'direction' => $nextDirection]) }}"
    class="flex items-center dark:text-white">
     {{ $label }}
     @if($sort === $column)
         <span class="ml-2">
             @if($direction === 'asc')
                 &uarr; <!-- Up arrow for ascending -->
             @else
                 &darr; <!-- Down arrow for descending -->
             @endif
         </span>
     @endif
 </a>
 