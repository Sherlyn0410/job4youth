<button {{ $attributes->merge(['type' => 'submit', 'class' => 'items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300']) }}>
    {{ $slot }}
</button>
