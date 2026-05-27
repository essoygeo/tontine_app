@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-amazon-orange dark:focus:border-amazon-orange focus:ring-amazon-orange dark:focus:ring-amazon-orange rounded-md shadow-sm']) }}>
