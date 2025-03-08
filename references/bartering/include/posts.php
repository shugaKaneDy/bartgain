<?php
  // You can simulate a slow server with sleep
  // sleep(2);

  require_once "../dbcon.php";

  $query = "SELECT * FROM items INNER JOIN users ON items.user_id = users.user_id ORDER BY items.item_id DESC LIMIT 8";


  $stmt = $conn->prepare($query);

  // Execute the query
  $stmt->execute();

  // Fetch all rows as associative array
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($results as $row) {
   ?>
    <div
        class="relative shadow-xl rounded-xl p-5 transform transition-transform duration-300 hover:scale-105 hover:shadow-2xl">
        <img src="../try-upload-file/<?= $row["url_picture"] ?>" alt="Image" class="w-full h-48 object-cover rounded-2xl mb-2" onclick="openModal()">
        <div class="flex flex-row mx-1">
            <div>
                <img src="img/profile.jpg" alt="maloi pic" class="w-10 h-auto rounded-full">
            </div>
            <div class="ml-2">
                <h3 class="text-gray-700 font-semibold text-base hover:underline"><a href="#"><?= $row["fullname"] ?></a></h3>
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
    <h3 class="text-green-600 font-poppins font-semibold m-1"><?= $row["swap_option"] ?></h3>
        <p class="ml-1"><?= $row["title"] ?></p> 
        <div class="flex justify-between">
            <p class="text-gray-500 mt-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6 inline-block">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
                <?= $row["address"] ?>
            </p>
            <p class="text-gray-500 mt-1">0.5 km</p>
        </div>
    </div>
   <?php 
  }




