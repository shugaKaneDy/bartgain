<?php
  session_start();
  require_once 'dbcon.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>Add New Item</title>
  <style>
    .autocomplete-suggestions {
      border: 1px solid #e3e3e3;
      max-height: 150px;
      overflow-y: auto;
      background-color: #fff;
      position: absolute;
      z-index: 1000;
      width: 100%;
    }
    .autocomplete-suggestion {
      padding: 10px;
      cursor: pointer;
    }
    .autocomplete-suggestion:hover {
      background-color: #e9ecef;
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <?php if(isset($_SESSION['message'])) : ?>
      <h5 class="alert alert-success"><?= $_SESSION['message'];?></h5>
    <?php 
      unset($_SESSION['message']);
      endif; ?>
    <h2>Add New Item</h2>
    <form action="include/add-item.inc.php" enctype="multipart/form-data" method="post">
      <div class="form-group position-relative mb-3">
        <label for="title">Title</label>
        <input type="text" name="title" class="form-control" id="title" placeholder="Enter item title">
      </div>
      <div class="form-group position-relative mb-3">
        <label for="formFile" class="form-label">Upload Image</label>
        <input class="form-control" type="file" id="formFile" name="image">
      </div>
      <div class="form-group position-relative mb-3">
        <label for="category">Category</label>
        <input type="text" name="category" class="form-control" id="category" placeholder="Enter category">
        <div id="category-suggestions"  class="autocomplete-suggestions"></div>
      </div>
      <div class="form-group position-relative mb-3">
        <label for="itemCondition">Item Condition</label>
        <input type="text" name="itemCondition" class="form-control" id="itemCondition" placeholder="Enter item condition">
        <div id="condition-suggestions" class="autocomplete-suggestions"></div>
      </div>
      <div class="form-group mb-3">
        <label for="description">Description</label>
        <textarea class="form-control" name="description" id="description" rows="3" placeholder="Enter item description"></textarea>
      </div>
      <div class="form-group mb-3">
        <label for="preferredMeetUp">Preferred Meet Up</label>
        <input type="text" name="preferredMeetUp" class="form-control" id="preferredMeetUp" placeholder="Enter preferred meet up location">
      </div>
      <button type="submit" class="btn btn-primary">Add Item</button>
    </form>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var categories = ["Electronics", "Furniture", "Clothing", "Books", "Other"];
      var itemConditions = ["New", "Like New", "Used", "For Parts"];

      function autocomplete(input, suggestions, suggestionsDiv) {
        input.addEventListener("input", function() {
          var value = this.value;
          closeSuggestions();
          if (!value) return;

          suggestionsDiv.style.display = 'block';

          suggestions.forEach(function(suggestion) {
            if (suggestion.toLowerCase().includes(value.toLowerCase())) {
              var suggestionElement = document.createElement("div");
              suggestionElement.classList.add("autocomplete-suggestion");
              suggestionElement.innerHTML = suggestion;
              suggestionElement.addEventListener("click", function() {
                input.value = suggestion;
                closeSuggestions();
              });
              suggestionsDiv.appendChild(suggestionElement);
            }
          });
        });

        document.addEventListener("click", function(e) {
          if (e.target !== input) {
            closeSuggestions();
          }
        });

        function closeSuggestions() {
          suggestionsDiv.innerHTML = '';
          suggestionsDiv.style.display = 'none';
        }
      }

      var categoryInput = document.getElementById("category");
      var categorySuggestionsDiv = document.getElementById("category-suggestions");
      autocomplete(categoryInput, categories, categorySuggestionsDiv);

      var conditionInput = document.getElementById("itemCondition");
      var conditionSuggestionsDiv = document.getElementById("condition-suggestions");
      autocomplete(conditionInput, itemConditions, conditionSuggestionsDiv);
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>
