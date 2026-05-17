<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-lime-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-lime-700 focus:bg-lime-700 active:bg-lime-800 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 shadow-md hover:shadow-lg transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
