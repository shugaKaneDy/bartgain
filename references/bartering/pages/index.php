<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../build/css/style.css">
    <style>
        .confetti-piece {
            position: absolute;
            will-change: transform, opacity;
        }
    </style>
    
</head>
<body class=" font-poppins" >
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
                    <img src="img/profile.jpg" alt="profile pic" class="w-10 h-auto rounded-full ">
                </button>
            
                <div id="dropdown-menu"
                    class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200  shadow-lg z-50">
                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 w-full">Sign Out</a>
                </div>
            </div>
        </nav>
    </header>


    <main class="container h-auto mx-auto mt-28 ">
        <div id="confetti-container" class="overflow-hidden"></div>
        <section >
            <div class="md:flex md:flex-row mt-28 text-center justify-center">
                <div class="md:w-2/5 flex flex-col justify-center">
                    <h2 class="font-poppins  text-4xl text-gray-600 mt-0 font-bold mb-5">
                        TRADE, DONATE YOUR STUFF ANYTIME,
                        ANYWHERE.
                    </h2>
                    <p class="font-poppins mt-2 text-lg text-gray-600 ">
                        Barter for a Better Tomorrow !
                    </p>
            
                    <div class="relative mb-4 lg:mb-0 max-lg:w-58 lg:w-auto inline-flex self-center my-5">
                        <input type="text" placeholder="Search for anything"
                            class="py-2 px-10  rounded-lg border border-gray-400 focus:outline-none focus:border-red-400 w-full md:w-auto">
                        <button type="button" class="absolute right-2 top-1/2 transform -translate-y-1/2 mx-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 inline-block text-green-400">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>  
        </section>
        <section >
            <div class="lg:flex lg:flex-row lg:justify-between items-center text-center p-5 mt-28">
                <div>
                    <h3 class="text-gray-500 m-2 font-semibold">DAILY DISCOVER</h3>
                </div>
                <div class="flex flex-wrap justify-center md:justify-start items-center ">
                    <a href="#"
                        class="bg-emerald-500 hover:bg-emerald-500 text-white rounded-xl p-2 px-5 mx-2 my-2 self-start shadow-lg font-semibold items-center">
                        ALL
                    </a>
                    <a href="#"
                        class="bg-slate-200 hover:bg-emerald-500 hover:text-white text-gray-500 rounded-xl p-2 px-5 mx-2 my-2 self-start shadow-lg font-semibold items-center">
                        LAPTOP
                    </a>
                    <a href="#"
                        class="bg-slate-200 hover:bg-emerald-500 hover:text-white text-gray-500 rounded-xl p-2 px-5 mx-2 my-2 self-start shadow-lg font-semibold items-center">
                        SHOES
                    </a>
                    <a href="#"
                        class="bg-slate-200 hover:bg-emerald-500 hover:text-white text-gray-500 rounded-xl p-2 px-5 mx-2 my-2 self-start shadow-lg font-semibold items-center">
                        MOBILES
                    </a>
                    <a href="#"
                        class="bg-slate-200 hover:bg-emerald-500 hover:text-white text-gray-500 rounded-xl p-2 px-5 mx-2 my-2 self-start shadow-lg font-semibold items-center">
                        HEADPHONES
                    </a>
                    <a href="#"
                        class="bg-slate-200 hover:bg-emerald-500 hover:text-white text-gray-500 rounded-xl p-2 px-5 mx-2 my-2 self-start shadow-lg font-semibold items-center">
                        TABLETS
                    </a>
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6 inline-flex hover:text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>
                <div>
                    <a href="#" class="text-gray-600">VIEW ALL
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="inline-block h-7 w-5 ml-1 text-gray-500 hover:text-gray-400 ">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>
        <section >
            <div id="item-posts" class="grid grid-flow-row grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10">
                
                
            </div>
        </section>

        <section>
            <!-- Modal -->
            <div id="itemModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 hidden items-center justify-center w-full overflow-y-auto">
                <div class="bg-white p-5 rounded-lg relative w-full max-w-2xl mx-auto mt-28">
                    <button class="absolute top-2 right-2 text-gray-400 hover:text-gray-600" onclick="closeModal()"></button>
                    <div class="flex flex-row items-center mb-4">
                        <img src="img/profile.jpg" alt="Profile Picture" class="w-12 h-12 rounded-full">
                        <div class="ml-4">
                            <h3 class="text-gray-700 font-semibold text-lg">Maloi Recalde</h3>
                            <div class="flex space-x-1">
                                <!-- Star Ratings -->
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
                    <img src="img/iphone12.webp" alt="Image" class="w-full h-auto object-cover rounded-2xl mb-4">
                    <h3 class="text-green-600 font-poppins font-semibold m-1">Swap</h3>
                    <p class="mb-1 font-semibold">iPhone 12 Pro Max</p>
                    <p class=" text-gray-600"><span class="text-gray-700 font-semibold">Description:</span> This is a well-maintained iPhone 12 Pro Max with no scratches and
                        in perfect working condition.</p>
                    <p class=" text-gray-600"><span class="text-gray-700 font-semibold">Condition:</span> Like New</p>
                    <button id="favoriteButton" class="text-red-400 hover:text-red-500 mb-4" onclick="toggleFavorite()">
                        <svg id="heartIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            class="size-7">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                        </svg>

                    </button>
                    <button class="absolute top-2 right-2 text-gray-400 hover:text-gray-600" onclick="closeModal()">Close</button>
                </div>
            </div>

        </section>

    </main>
    <script src="../js/plugins/jquery/jquery.js"></script>
    <script src="../js/plugins/sweetalert2/swal.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

                const confettiContainer = document.getElementById('confetti-container');

                function createConfettiPiece() {
                    const piece = document.createElement('div');
                    piece.classList.add('confetti-piece');
                    piece.style.width = `${Math.random() * 10 + 5}px`;
                    piece.style.height = `${Math.random() * 10 + 5}px`;
                    piece.style.backgroundColor = `hsl(${Math.random() * 360}, 100%, 50%)`;
                    piece.style.top = `${Math.random() * 100}%`;
                    piece.style.left = `${Math.random() * 100}%`;
                    piece.style.opacity = `${Math.random()}`;
                    piece.style.transform = `rotate(${Math.random() * 360}deg)`;
                    piece.style.transition = `transform 2s ease-out, opacity 2s ease-out`;

                    confettiContainer.appendChild(piece);

                    setTimeout(() => {
                        piece.style.transform = `translateY(${window.innerHeight}px) rotate(${Math.random() * 360}deg)`;
                        piece.style.opacity = '0';
                        setTimeout(() => confettiContainer.removeChild(piece), 2000);
                    }, 100);
                }

                for (let i = 0; i < 100; i++) {
                    setTimeout(createConfettiPiece, i * 50);
                }
            });
            
        document.getElementById('menu-toggle').addEventListener('click', function () {
            var menu = document.getElementById('menu');
            menu.classList.toggle('hidden');
        });
         //modal logic
        let isFavorited = false;

            function openModal() {
                document.getElementById('itemModal').classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('itemModal').classList.add('hidden');
            }

            function toggleFavorite() {
                const heartIcon = document.getElementById('heartIcon');
                isFavorited = !isFavorited;

                if (isFavorited) {
                    heartIcon.classList.add('fill-red-500');
                    // Save to localStorage (or backend)
                    localStorage.setItem('favoritedItem', JSON.stringify({ option:'Swap', name: 'iPhone 12 Pro Max', image: 'img/iphone12.webp', description: 'This is a well-maintained iPhone 12 Pro Max with no scratches and in perfect working condition.', condition: 'Like New' }));
                } else {
                    heartIcon.classList.remove('text-red-500');
                    // Remove from localStorage (or backend)
                    localStorage.removeItem('favoritedItem');
                }
            }
            
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
        
        // My code
        var container = $('#item-posts');

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


        $('document').ready(function(){
            $.ajax({
                url: "../include/posts.php",
                method: "GET",
                success: function(response) {
                    console.log('Result: ' + response);

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
        });

    </script>
</body>
</html>
