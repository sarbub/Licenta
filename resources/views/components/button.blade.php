<button {{ $attributes->merge([
'type' => 'submit', 
'class' => '
inline-flex items-center px-4 py-2 bg-[#007bff] 
border border 
border-transparent rounded-md font-semibold text-xs 
text-white uppercase tracking-widest 
focus:bg-gray-700 active:bg-gray-900 
focus:outline-none focus:ring-2 
focus:ring-indigo-500 
focus:ring-offset-2 
disabled:opacity-50 transition ease-in-out duration-150 
hover:bg-[#0056b3]',

]) }}>
    {{ $slot }}
</button>