<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-amazon-orange border border-transparent rounded-md font-semibold text-xs text-amazon-dark uppercase tracking-widest hover:bg-amazon-yellow focus:bg-amazon-yellow active:bg-amazon-orange focus:outline-none focus:ring-2 focus:ring-amazon-orange focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 shadow-lg shadow-amazon-orange/20']) }}>
    {{ $slot }}
</button>
