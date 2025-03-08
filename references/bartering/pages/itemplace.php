<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITEM PLACE</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../build/css/style.css">
</head>

<body class="font-poppins ">
    <header
        class="lg:flex lg:flex-row lg:justify-around items-center text-center p-4 shadow-md fixed top-0 w-full z-50 bg-white mb-28">
        <div class="flex items-center justify-between w-full lg:w-auto">
            <div class="flex items-center">
                <button id="menu-toggle" class="lg:hidden text-green-500 hover:text-red-400 p-4 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="bg-green-400 w-10 h-10 rounded-full shadow-lg"></div>
                <h1 class="text-3xl text-green-400 ml-2 font-bold">BartGain</h1>
            </div>
    
        </div>
    
        <nav id="menu" class="hidden lg:flex flex-col lg:flex-row lg:items-center w-full lg:w-auto mt-4 lg:mt-0 lg:ml-4">
            <a href="home.html" class="text-green-500 hover:text-red-400 p-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 inline-block text-red-400 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                HOME
            </a>
            <a href="itemplace.html" class="text-green-500 hover:text-red-400 p-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 inline-block text-red-400 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                </svg>
                OFFERS
            </a>
            <a href="messages.html" class="text-green-500 hover:text-red-400 p-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 inline-block text-red-400 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z" />
                </svg>
                CHAT
            </a>
            <a href="favorites.html" class="text-green-500 hover:text-red-400 p-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 inline-block text-red-400 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                </svg>
                FAVORITES
            </a>
            <a href="#" class="text-green-500 hover:text-red-400 p-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 inline-block text-red-400 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>
                NOTIFICATIONS
            </a>
    
            <div class="relative">
                <button id="account-button"
                    class="flex items-center space-x-2 p-2 bg-white border rounded-full shadow hover:bg-gray-100 focus:outline-none focus:ring">
                    <img src="../build/img/profile.jpg" alt="profile pic" class="w-10 h-auto rounded-full ">
                </button>
    
                <div id="dropdown-menu"
                    class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200  shadow-lg z-50">
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 w-full">Sign Out</a>
                </div>
            </div>
        </nav>
    </header>
    <main class=" container flex my-10  flex-col md:flex-row shadow-md  mx-auto mt-28 ">

        <section class="flex shadow-md">
            <div class="w-full lg:w-96 h-screen bg-white text-gray-700 flex-col lg:fix hidden lg:flex">
                <nav class="flex-1">
                    <ul>
                        <li class="p-4 hover:bg-green-100 hover:rounded-md">
                            
                            <a href="#" class="flex items-center space-x-3">
                            <div class="flex flex-row mr-2 items-center">
                                <div><img src="../build/img/profile.jpg" alt="maloi pic" class="w-9 h-auto rounded-full">
                                </div>
                                <div class="flex flex-row ml-1">
                                    <div>
                                        <h3 class="text-gray-600 font-semibold text-base hover:underline"><?= $_SESSION["user_details"]["fullname"] ?></h3>
                            
                                    </div>
                            
                                </div>
                            </div>
                            </a>
                        </li>
                        <li class="p-4 hover:bg-green-100 hover:rounded-md items-center">
                            <a href="uploadItem.html" class="flex items-center font-medium space-x-3">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="w-6 h-auto mr-2 inline-block">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                </svg>

                          </span>
                            UPLOAD ITEM
                            </a>
                        </li>
                        <li class="p-4 hover:bg-green-100 hover:rounded-md items-center">
                            <a href="saveplan.html" class="flex items-center font-medium space-x-3">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-6 h-auto mr-2 inline-block">
                                        <path fill-rule="evenodd"
                                            d="M6.32 2.577a49.255 49.255 0 0 1 11.36 0c1.497.174 2.57 1.46 2.57 2.93V21a.75.75 0 0 1-1.085.67L12 18.089l-7.165 3.583A.75.75 0 0 1 3.75 21V5.507c0-1.47 1.073-2.756 2.57-2.93Z"
                                            clip-rule="evenodd" />
                                    </svg>
                        
                                </span>
                                SAVE PLAN
                            </a>
                        </li>
                        <li class="p-4 hover:bg-green-100 hover:rounded-md items-center">
                            <a href="#" class="flex items-center font-medium space-x-3">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-auto mr-2 inline-block">
                                        <path fill-rule="evenodd"
                                            d="M11.828 2.25c-.916 0-1.699.663-1.85 1.567l-.091.549a.798.798 0 0 1-.517.608 7.45 7.45 0 0 0-.478.198.798.798 0 0 1-.796-.064l-.453-.324a1.875 1.875 0 0 0-2.416.2l-.243.243a1.875 1.875 0 0 0-.2 2.416l.324.453a.798.798 0 0 1 .064.796 7.448 7.448 0 0 0-.198.478.798.798 0 0 1-.608.517l-.55.092a1.875 1.875 0 0 0-1.566 1.849v.344c0 .916.663 1.699 1.567 1.85l.549.091c.281.047.508.25.608.517.06.162.127.321.198.478a.798.798 0 0 1-.064.796l-.324.453a1.875 1.875 0 0 0 .2 2.416l.243.243c.648.648 1.67.733 2.416.2l.453-.324a.798.798 0 0 1 .796-.064c.157.071.316.137.478.198.267.1.47.327.517.608l.092.55c.15.903.932 1.566 1.849 1.566h.344c.916 0 1.699-.663 1.85-1.567l.091-.549a.798.798 0 0 1 .517-.608 7.52 7.52 0 0 0 .478-.198.798.798 0 0 1 .796.064l.453.324a1.875 1.875 0 0 0 2.416-.2l.243-.243c.648-.648.733-1.67.2-2.416l-.324-.453a.798.798 0 0 1-.064-.796c.071-.157.137-.316.198-.478.1-.267.327-.47.608-.517l.55-.091a1.875 1.875 0 0 0 1.566-1.85v-.344c0-.916-.663-1.699-1.567-1.85l-.549-.091a.798.798 0 0 1-.608-.517 7.507 7.507 0 0 0-.198-.478.798.798 0 0 1 .064-.796l.324-.453a1.875 1.875 0 0 0-.2-2.416l-.243-.243a1.875 1.875 0 0 0-2.416-.2l-.453.324a.798.798 0 0 1-.796.064 7.462 7.462 0 0 0-.478-.198.798.798 0 0 1-.517-.608l-.091-.55a1.875 1.875 0 0 0-1.85-1.566h-.344ZM12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                                SETTINGS
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </section>

        <section id="item-lists" class="flex-1 p-8 items-center">
            <!-- <div class="container flex flex-col justify-center items-center">
                <div id="item-container" class="bg-white shadow-md rounded-xl mb-4">
                    <div class="flex items-center p-4">
                        <img src="img/profile.jpg" alt="maloi pic" class="w-12 h-12 rounded-full">
                        <div class="ml-4">
                            <h4 class="font-bold">Maloi Ricalde</h4>
                            <div>
                                <div class="flex space-x-1">
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.964a1 1 0 00.95.69h4.181c.969 0 1.371 1.24.588 1.81l-3.388 2.465a1 1 0 00-.363 1.118l1.287 3.964c.3.921-.755 1.688-1.54 1.118L10 13.432l-3.388 2.465c-.784.57-1.84-.197-1.54-1.118l1.287-3.964a1 1 0 00-.363-1.118L2.609 9.391c-.783-.57-.38-1.81.588-1.81h4.181a1 1 0 00.95-.69l1.286-3.964z" />
                                    </svg>
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.964a1 1 0 00.95.69h4.181c.969 0 1.371 1.24.588 1.81l-3.388 2.465a1 1 0 00-.363 1.118l1.287 3.964c.3.921-.755 1.688-1.54 1.118L10 13.432l-3.388 2.465c-.784.57-1.84-.197-1.54-1.118l1.287-3.964a1 1 0 00-.363-1.118L2.609 9.391c-.783-.57-.38-1.81.588-1.81h4.181a1 1 0 00.95-.69l1.286-3.964z" />
                                    </svg>
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.964a1 1 0 00.95.69h4.181c.969 0 1.371 1.24.588 1.81l-3.388 2.465a1 1 0 00-.363 1.118l1.287 3.964c.3.921-.755 1.688-1.54 1.118L10 13.432l-3.388 2.465c-.784.57-1.84-.197-1.54-1.118l1.287-3.964a1 1 0 00-.363-1.118L2.609 9.391c-.783-.57-.38-1.81.588-1.81h4.181a1 1 0 00.95-.69l1.286-3.964z" />
                                    </svg>
                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.964a1 1 0 00.95.69h4.181c.969 0 1.371 1.24.588 1.81l-3.388 2.465a1 1 0 00-.363 1.118l1.287 3.964c.3.921-.755 1.688-1.54 1.118L10 13.432l-3.388 2.465c-.784.57-1.84-.197-1.54-1.118l1.287-3.964a1 1 0 00-.363-1.118L2.609 9.391c-.783-.57-.38-1.81.588-1.81h4.181a1 1 0 00.95-.69l1.286-3.964z" />
                                    </svg>
                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.964a1 1 0 00.95.69h4.181c.969 0 1.371 1.24.588 1.81l-3.388 2.465a1 1 0 00-.363 1.118l1.287 3.964c.3.921-.755 1.688-1.54 1.118L10 13.432l-3.388 2.465c-.784.57-1.84-.197-1.54-1.118l1.287-3.964a1 1 0 00-.363-1.118L2.609 9.391c-.783-.57-.38-1.81.588-1.81h4.181a1 1 0 00.95-.69l1.286-3.964z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <h2 class="text-green-600 font-poppins font-semibold m-1">Swap</h2>
                        <h3 class="ml-1 font-semibold">iPhone 12 Pro Max</h3>
                        <p>This is a detailed description of the iPhone 12 Pro Max. It includes information about its features,
                            specifications, condition, and any other relevant details.</p>
                        <div class="flex flex-row gap-4 mt-2">
                            <p class="text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-6 inline-block text-green-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                                Dasmariñas, Cavite
                            </p>
                            <p class="text-gray-700">0.5 km</p>
                        </div>
                        <img src="img/iphone12.webp" alt="Image 1" class="mt-2 rounded-lg content-center">
                        <div class="flex flex-row gap-2 items-center mt-5">
                            <button id="favoriteButton" class="bg-gray-200 text-red-500 px-4 py-2 rounded-full" onclick="toggleFavorite()">
                                <svg id="heartIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-7">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                </svg>
                            </button>
                            <button class="bg-green-400 text-white px-4 py-2 rounded font-semibold "><a href="messages.html">Make an Offer</a> </button>
                        </div>
                    </div>
                </div>

            </div> -->
        </section>
        
    </main>

    <script src="../js/plugins/jquery/jquery.js"></script>
    <script src="../js/plugins/sweetalert2/swal.js"></script>
    <script>

        //my Code
        $('document').ready(function(){
            
            $.ajax({
                url: "../include/itemplace-post.php",
                method: "GET",
                success: function(response) {
                    // console.log('Result: ' + response);

                    // Append the response HTML to the container
                    appendToDiv(container, response);
                    // container.html(response);

                    // Optionally, if you have a spinner or pagination handling, you can uncomment these lines
                    // hideSpinner();
                    // setCurrentPage(nex_page);
                    // showLoadMore();
                },
                error: function(xhr, status, error) {
                    console.error('Error: ' + error);
                    // Handle errors here
                }
            });
            $('#myButton').on('click', function() {
                console.log("hello world");
            });
            

        });

        $(document).on('click', '.favoriteButton',function() {

            console.log($(this).hasClass('fill-red-500'))

            if ($(this).find('svg').hasClass('fill-red-500')) {
                $(this).find('svg').removeClass('fill-red-500');
            } else {
                $(this).find('svg').addClass('fill-red-500');
            }
            
        })

        function favorite(value) {
            console.log("favorite: ", value );
            var parent = $(this).parent();
            console.log(parent.attr('id'));
        }

        

        //sherwin
        document.getElementById('menu-toggle').addEventListener('click', function () {
                var menu = document.getElementById('menu');
                menu.classList.toggle('hidden');
            });

            const accountButton = document.getElementById('account-button');
                const dropdownMenu = document.getElementById('dropdown-menu');

                accountButton.addEventListener('click', () => {
                    dropdownMenu.classList.toggle('hidden');
                });

                window.addEventListener('click', (e) => {
                    if (!accountButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.add('hidden');
                    }
                });

                 let isFavorited = false;

                function toggleFavorite() {
                        const heartIcon = document.getElementById('heartIcon');
                        isFavorited = !isFavorited;

                        if (isFavorited) {
                            heartIcon.classList.add('fill-red-500');
                            // Save to localStorage (or backend)
                            localStorage.setItem('favoritedItem', JSON.stringify({ option:'Swap',name: 'iPhone 12 Pro Max', image: 'img/iphone12.webp', description: 'This is a well-maintained iPhone 12 Pro Max with no scratches and in perfect working condition.', condition: 'Like New' }));
                        } else {
                            heartIcon.classList.remove('text-red-500');
                            // Remove from localStorage (or backend)
                            localStorage.removeItem('favoritedItem');
                        }
                    }

        // My code
        var container = $('#item-lists');

        function appendToDiv(div, new_html) {
            // Put the new HTML into a temp div
            // This causes browsers to parse it as elements
            var temp = $('<div>');
            temp.html(new_html);

            // Then we can find and work those elements
            // Use firstElementChild because of how DOM treats whitespace.

            // var class_name = temp.firstElementChild.className;
            // var item = temp.getElementByClass(class_name);
            div.append(temp.children());
            
        }

        


        

        
        

    </script>
</body>

</html>