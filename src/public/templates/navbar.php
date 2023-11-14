<?php
?>
<link rel="stylesheet" href="/public/css/lib/index/navbar.css">
<!-- <aside>

</aside> -->
<nav class="navbar">
    <div>
        <a href="/dashboard"><img class="logo" src="/public/logo.webp" alt="Bondflix logo"></a>
        <div id="menu-left">
            <a href="/mylist">My List</a>
            <a href="/dashboard">Movies</a>
            <a href="/activate/subscription">Activate Subscription</a>
            <a href="/premium/creators">Creator List</a>
        </div>
    </div>
    <div id="menu-right">
        <div class="hamburger-button">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"></path>
            </svg>
        </div>
        <input type="text" class="search-bar" placeholder="Search a movie" id="navbar-search-input">
        <div class="filter-container">
            <button class="navbar-filter-button" id="sort-filter-button">Sort Title ↑</button>
            <select id="genre-dropdown">
            </select>
            <input type="date" id="release-date-filter">
            <button class="navbar-filter-button" id="navbar-filter-button">Filter ✗</button>
        </div>
        <button class="navbar-account-button">
            <img src="/public/avatar.png" id='profile-picture-navbar' alt="profile picture">
        </button>
        <div class="account-menu">
            <ul>
                <li>
                    <a href="/account">Account</a>
                </li>
                <li class="account-menu-for-phone">
                    <a href="/mylist">My List</a>
                </li>
                <li class="account-menu-for-phone">
                    <a href="/dashboard">Movies</a>
                </li>
                <li class="account-menu-for-phone"
                    <a href="/activate/subscription">Activate Subscription</a>
                <li>
                <li class="account-menu-for-phone"
                <a href="/premium/creators">Premium Creator List</a>
                <li>
                    <a href="" onclick="logout()">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<script src="/public/js/navbar.js"></script>
