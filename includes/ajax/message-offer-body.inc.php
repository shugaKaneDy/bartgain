<?php

require_once "functions.php";



if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

  require_once "../dbh.inc.php";
  session_start();
  $currentTime  = date("Y-m-d H:i:s");

  // print_r($_GET);
  $offerId = $_GET['offerId'];
  // echo $offerId;
  // exit();

  ?>
  <div class="row border-bottom first-top">
    <div class="col-8 p-2 d-flex align-items-center">
      <img src="assets/profile.jpg" alt="" class="img-fluid rounded-pill" style="height: 40px; width: 40px">
      <div class="ps-2">
        <p class="fw-bold m-0">Kane Gericson Tagay</p>
        <p class="fw-bold m-0">5 <i class="bi bi-star-fill text-warning"></i></p>
      </div>
    </div>
    <div class="col-4 d-flex gap-2 justify-content-end align-items-center d-md-none">
      <button class="btn btn-white forChats tt" data-bs-placement="bottom" data-bs-title="Chats">
        <i class="bi bi-chat-dots"></i>
      </button>
      <button class="btn btn-white forPlan tt" data-bs-placement="bottom" data-bs-title="Plan ">
        <i class="bi bi-info-circle-fill"></i>
      </button>
    </div>
  </div>
  <div class="row border-bottom p-2 second-top shadow-sm">
    <div class="col-6 p-1">
      <button class="btn btn-outline-secondary w-100">
        View Item
      </button>
    </div>
    <div class="col-6 p-1">
      <button class="btn btn-outline-success bg-green w-100">
        View Offer
      </button>
    </div>
  </div>
  <div class="third-top p-2">
    <div class="row mb-3">
      <div class="col-8 col-md-5">
        <div class="w-100 border border-secondary bg-body-tertiary rounded py-3">
          <p class="text-center fw-bold m-0">Sent an offer</p>
          <div class="px-4">
            <button class="btn btn-outline-success bg-green btn-sm w-100">View Offer</button>
          </div>
        </div>
        <p class="m-0 smaller-text">10/3/2024 10:40 PM</p>
      </div>
    </div>
    <div class="row justify-content-end mb-3">
      <div class="col-10 col-md-8">
        <div class="w-100 border border-warning bg-warning text-white rounded p-2">
          <p class="m-0 message-text">Hello po. Available pa po ba?</p>
        </div>
        <p class="m-0 smaller-text float-end">10/3/2024 10:40 PM</p>
      </div>
    </div>
    <div class="row justify-content-start">
      <div class="col-10 col-md-8">
        <div class="w-100 border border-secondary bg-body-tertiary rounded p-2">
          <p class="m-0 message-text">Yes po. Ready to plan na po?</p>
        </div>
        <p class="m-0 smaller-text">10/3/2024 10:40 PM</p>
      </div>
    </div>
    <div class="row justify-content-start">
      <div class="col-10 col-md-8">
        <div class="w-100 border border-secondary bg-body-tertiary rounded p-2">
          <p class="m-0 message-text">Yes po. Ready to plan na po?</p>
        </div>
        <p class="m-0 smaller-text">10/3/2024 10:40 PM</p>
      </div>
    </div>
    <div class="row justify-content-start">
      <div class="col-10 col-md-8">
        <div class="w-100 border border-secondary bg-body-tertiary rounded p-2">
          <p class="m-0 message-text">Yes po. Ready to plan na po?</p>
        </div>
        <p class="m-0 smaller-text">10/3/2024 10:40 PM</p>
      </div>
    </div>
    <div class="row justify-content-start">
      <div class="col-10 col-md-8">
        <div class="w-100 border border-secondary bg-body-tertiary rounded p-2">
          <p class="m-0 message-text">Yes po. Ready to plan na po?</p>
        </div>
        <p class="m-0 smaller-text">10/3/2024 10:40 PM</p>
      </div>
    </div>
    <div class="row justify-content-start">
      <div class="col-10 col-md-8">
        <div class="w-100 border border-secondary bg-body-tertiary rounded p-2">
          <p class="m-0 message-text">Yes po. Ready to plan na po?</p>
        </div>
        <p class="m-0 smaller-text">10/3/2024 10:40 PM</p>
      </div>
    </div>
  </div>
  <div class="row bg-white p-2 border-top bottom-top">
    <form action="" class="d-flex gap-2">
      <input name="message" class="flex-grow-1 form-control my-input" placeholder="Aa">
      <button class="btn btn-info border border-dark">
        <i class="bi bi-send"></i>
      </button>
    </form>
  </div>

  <?php
  


}

?>