<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'E Shop') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- n8n Chat -->
    <link
        href="https://cdn.jsdelivr.net/npm/@n8n/chat/dist/style.css"
        rel="stylesheet"
    />

    <!-- Your CSS -->
    <link href="{{ asset('css/modern-ui.css') }}" rel="stylesheet">
</head>

<body>

@if(session('user_id'))

    @php

        $userId = session('user_id');
        $userName = session('user_name');
        $role = session('role');

        $timestamp = time();

        $signature = hash_hmac(
            'sha256',
            $userId.'.'.$timestamp,
            config('services.n8n.secret')
        );

    @endphp

    <script type="module">

import { createChat } from 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js';

createChat({

    webhookUrl: 'http://localhost:5678/webhook/fcbb22c8-64bb-4c9c-92d0-e0a96c13c114',

    mode: 'window',

    initialMessages: [
        'Welcome to E Shop!',
        'I can help you search products, check stock, view your orders, and answer company policy questions.'
    ],

    metadata: {

        user_id: "{{ $userId }}",
        user_name: "{{ $userName }}",
        role: "{{ $role }}",

        timestamp: "{{ $timestamp }}",
        signature: "{{ $signature }}"

    }

});


// Convert product tag into button

function convertProductButton() {

    const chatMessages = document.querySelectorAll('*');

    chatMessages.forEach(function(element) {

        if (
            element.children.length === 0 &&
            element.textContent.includes('[VIEW_PRODUCT:')
        ) {

            const match = element.textContent.match(
                /\[VIEW_PRODUCT:(.*?)\]/
            );

            if(match){

                const slug = match[1];

                element.innerHTML = `
                    <a href="/product/${slug}" 
                       class="btn btn-primary">
                        View Product
                    </a>
                `;

            }

        }

    });

}


const productButtonObserver = new MutationObserver(function(){

    convertProductButton();

});


productButtonObserver.observe(document.body, {

    childList:true,
    subtree:true

});


convertProductButton();


</script>

@endif


<nav class="app-navbar">

    <div class="app-navbar__utility">
        <div class="app-shell app-navbar__utility-inner">
            <span>Fast delivery, secure checkout, and fresh deals every week.</span>
            <span class="app-navbar__utility-note">Customer support ready when you need it.</span>
        </div>
    </div>

    <div class="app-shell app-navbar__inner">

        <a class="navbar-brand" href="/shop" aria-label="E Shop home">
            <span class="brand-mark">E</span>
            <span>E Shop</span>
        </a>

        <button class="app-navbar__toggle" type="button" data-menu-toggle aria-controls="siteNavigation" aria-expanded="false" aria-label="Open menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="app-navbar__links" id="siteNavigation">

            <div class="app-drawer__head">
                <span>Menu</span>
                <button class="app-drawer__close" type="button" data-menu-close aria-label="Close menu">Close</button>
            </div>

            @if(session('user_id'))

                <span class="nav-link app-navbar__greeting">
                    Hi, {{ session('user_name') }}
                </span>

                @if(session('role')=='customer')

                    <a class="nav-link" href="/cart">
                        Cart
                    </a>

                    <a class="nav-link" href="/orders">
                        Orders
                    </a>

                @endif

                @if(session('role')=='admin')

                    <a class="nav-link" href="/admin/dashboard">
                        Admin
                    </a>

                    <a class="nav-link" href="/admin/product">
                        Add Products
                    </a>

                @endif

                <a class="nav-link" href="/logout">
                    Logout
                </a>

            @else

                <a class="nav-link" href="/login">
                    Login
                </a>

                <a class="nav-link nav-link--accent" href="/register">
                    Create Account
                </a>

            @endif

        </div>

    </div>

</nav>

<div class="app-drawer-backdrop" data-menu-close hidden></div>

<main class="app-shell app-page">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.querySelector('[data-menu-toggle]');
        const drawer = document.getElementById('siteNavigation');
        const backdrop = document.querySelector('.app-drawer-backdrop');
        const closeControls = document.querySelectorAll('[data-menu-close]');

        if (toggle && drawer) {
            function setDrawer(open) {
                drawer.classList.toggle('is-open', open);
                document.body.classList.toggle('drawer-open', open);
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');

                if (backdrop) {
                    backdrop.hidden = !open;
                }
            }

            toggle.addEventListener('click', function () {
                setDrawer(!drawer.classList.contains('is-open'));
            });

            closeControls.forEach(function (control) {
                control.addEventListener('click', function () {
                    setDrawer(false);
                });
            });

            drawer.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', function () {
                    setDrawer(false);
                });
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    setDrawer(false);
                }
            });
        }

        function styleChatLinks() {
            document.querySelectorAll('[class*="chat"] a, [id*="chat"] a').forEach(function (link) {
                if (link.closest('.product-card') || link.classList.contains('navbar-brand') || link.classList.contains('nav-link')) {
                    return;
                }

                const text = link.textContent.trim().toLowerCase();

                if (text.includes('view') || text.includes('product') || link.href.includes('/product/')) {
                    link.classList.add('chat-action-link');
                }
            });
        }

        styleChatLinks();

        const observer = new MutationObserver(styleChatLinks);
        observer.observe(document.body, { childList: true, subtree: true });
    });
</script>

</body>
</html>
