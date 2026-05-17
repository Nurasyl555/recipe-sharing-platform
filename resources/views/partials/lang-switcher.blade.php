<div class="ms-4 flex items-center gap-3">
    <a href="{{ route('lang.switch', 'en') }}" class="text-sm {{ app()->getLocale() == 'en' ? 'font-bold' : '' }}">EN</a>
    <a href="{{ route('lang.switch', 'ru') }}" class="text-sm {{ app()->getLocale() == 'ru' ? 'font-bold' : '' }}">RU</a>
    <a href="{{ route('lang.switch', 'kk') }}" class="text-sm {{ app()->getLocale() == 'kk' ? 'font-bold' : '' }}">KK</a>
</div>