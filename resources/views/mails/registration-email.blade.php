@extends('mails.mail-template')
@section('content')
    <div style="text-align: center;">
        <h1 style="font-size: 28px; font-weight: bold; margin-bottom: 20px; color: #333;">
            @lang('translations.Congratulations and welcome, ') {{ $user?->name }}!
        </h1>

        <p style="font-size: 16px; color: #555; margin-bottom: 16px;">
            @lang('translations.We’re excited to have you at Eventify! We’re here to make planning your event easy and enjoyable.')
        </p>

        <p style="font-size: 16px; color: #555; margin-bottom: 16px;">
            @lang('translations.Your account has been successfully registered.')
        </p>

        <p style="font-size: 16px; color: #555; margin-bottom: 30px;">
            @lang('translations.If you have any questions or need assistance, feel free to reach out. We’re happy to help!')
        </p>

        <a href="#"
           style="
           background-color: #ebca7e;
                color: #fff;
                padding: 8px 20px;
                text-decoration: none;
                font-weight: bold;
                border-radius: 20px;
                font-size: 14px;
                display: inline-block;
                transition: background-color 0.3s ease;
            "
           onmouseover="this.style.backgroundColor='#d1b569'"
           onmouseout="this.style.backgroundColor='#ebca7e'">
            @lang('translations.Create your first invitation')
            <span style="display: inline-block; vertical-align: middle; margin-left: 6px;">
                 <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#fff" viewBox="0 0 24 24">
                    <path d="M13 5l7 7-7 7v-4h-8v-6h8V5z"/>
                 </svg>
            </span>
        </a>
    </div>
@endsection
