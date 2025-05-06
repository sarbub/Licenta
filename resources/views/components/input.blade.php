@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
'class' => 'border-gray-300
focus:border-[#007bff]
focus:ring-[#007bff]
rounded-md shadow-sm transition duration-150 ease-in-out'

]) !!}>