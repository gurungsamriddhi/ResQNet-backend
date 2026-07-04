@props([
    'id',
    'name',
    'label',
    'placeholder' => '••••••••',
    'required' => true,
    'autofocus' => false,
    'theme' => 'light',
    'size' => 'md',
    'showRequired' => false,
])

@php
    $isDark = $theme === 'dark';
    $isSmall = $size === 'sm';

    $labelClass = $isDark
        ? 'block text-sm font-medium text-gray-300 ' . ($isSmall ? 'mb-1.5' : 'mb-2')
        : 'block text-sm font-medium text-gray-700 ' . ($isSmall ? 'mb-1.5' : 'mb-2');

    $inputClass = $isDark
        ? 'w-full px-4 pr-11 ' . ($isSmall ? 'py-2.5 text-sm' : 'py-3') . ' bg-gray-900/50 border border-gray-600/50 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition'
        : 'w-full px-4 pr-11 ' . ($isSmall ? 'py-2.5 text-sm' : 'py-3') . ' bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition';

    $toggleClass = $isDark
        ? 'absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-300 transition'
        : 'absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition';
@endphp

<div {{ $attributes->merge(['class' => $isSmall ? '' : 'mb-5']) }}>
    <label for="{{ $id }}" class="{{ $labelClass }}">
        {{ $label }}@if($showRequired)<span class="text-red-500"> *</span>@endif
    </label>
    <div class="relative">
        <input
            id="{{ $id }}"
            type="password"
            name="{{ $name }}"
            @if($required) required @endif
            @if($autofocus) autofocus @endif
            class="{{ $inputClass }}"
            placeholder="{{ $placeholder }}"
        >
        <button
            type="button"
            class="{{ $toggleClass }}"
            onclick="togglePasswordVisibility(this)"
            aria-label="Show password"
        >
            <svg data-eye-show class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <svg data-eye-hide class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
            </svg>
        </button>
    </div>
</div>

@once
    <script>
        function togglePasswordVisibility(button) {
            const input = button.parentElement.querySelector('input');
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            button.querySelector('[data-eye-show]').classList.toggle('hidden', show);
            button.querySelector('[data-eye-hide]').classList.toggle('hidden', !show);
            button.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        }
    </script>
@endonce
