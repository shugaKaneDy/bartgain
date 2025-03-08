<?php
  // You can simulate a slow server with sleep
  // sleep(2);
  session_start();
  require_once "../dbcon.php";

  $query = "SELECT * FROM items INNER JOIN users ON items.user_id = users.user_id ORDER BY items.item_id DESC";


  $stmt = $conn->prepare($query);

  // Execute the query
  $stmt->execute();

  // Fetch all rows as associative array
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($results as $row) {
   ?>
    <div class="container flex flex-col justify-center items-center">
      <div id="item-container" class="bg-white shadow-md rounded-xl mb-4">
          <div class="flex items-center p-4">
              <img src="../build/img/profile.jpg" alt="maloi pic" class="w-12 h-12 rounded-full">
              <div class="ml-4">
                  <h4 class="font-bold"><?= $row["fullname"] ?></h4>
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
              <h2 class="text-green-600 font-poppins font-semibold m-1"><?= $row["swap_option"] ?></h2>
              <h3 class="ml-1 font-semibold"><?= $row["title"] ?></h3>
              <p><?= $row["description"] ?></p>
                <p class="text-gray-700 mt-4">
                    Category: <span class="p-1"><?= $row["category"]?></span>
                </p>
              <div class="flex flex-row gap-4 mt-2">
                <p class="text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6 inline-block text-green-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                    <?= $row["address"] ?>
                </p>
                <p class="text-gray-700">0.5 km</p>
              </div>
              <img src="../try-upload-file/<?= $row["url_picture"] ?>" alt="Image 1" class="mt-2 rounded-lg content-center">
              <div id="<?= $row["item_id"] ?>"   class="flex flex-row gap-2 items-center mt-5">
                <button id="mybutton"  onclick="favorite(value=<?= $row['item_id'] ?>)">fav</button>
                <input type="hidden" id="thisValue" value="<?= $row["item_id"] ?>">
                <button id="favoriteButton"  value="<?= $row["item_id"] ?>" class="bg-gray-200 text-red-500 px-4 py-2 rounded-full favoriteButton" onclick="toggleFavorite()">
                    <svg id="heartIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-7">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                </button>
                <form action="../make-offer.php" method="POST">
                <input type="hidden" name="item_id" value="<?= $row["item_id"] ?>">
                <button type="submit" class="bg-green-400 text-white px-4 py-2 rounded font-semibold ">Make an Offer </button>
                </form>
              </div>
          </div>
      </div>

    </div>
   <?php 
  }




