<div class="row p-3 border shadow-sm mb-2 bg-warning">
        <div class="col-12 col-md-5 bg-success-subtle p-2 d-flex flex-column justify-content-center align-items-center rounded">
          <p class="h5">
            <span class="badge text-bg-success">Your Item</span>
          </p>
          <?php if(in_array($itemExt, $allowedImages)): ?>
            <img src="item-uploads/<?= $itemFirstFile ?>" alt="" class="img-item-size mb-2 img-thumbnail border border-success border-2">
          <?php else: ?>
            <video src="item-uploads/<?= $itemFirstFile ?>" class="img-item-size mb-2 img-thumbnail border border-success border-2"></video>
          <?php endif ?>
          <p class="fw-bold h6"><?= $offerPartner['item_title'] ?></p>
          
          <button value="<?= $offerPartner['item_random_id'] ?>" class="forViewItemModal btn btn-outline-success bg-green"
          data-bs-toggle="modal" data-bs-target="#itemModalView"
          >
            View Item
          </button>

        </div>
        <div class="col-12 col-md-2 d-flex flex-column align-items-center justify-content-center py-3">
          <p class="m-0 fs-3 px-3 py-1 rounded text-white bg-success bg-gradient shadow"><i class="bi bi-arrow-left-right"></i></p>
        </div>
        <div class="col-12 col-md-5 bg-secondary-subtle p-2 d-flex flex-column justify-content-center align-items-center rounded">
          <p class="h5">
            <span class="badge text-bg-secondary">Partner Offer</span>
          </p>
          <?php if(in_array($offerExt, $allowedImages)): ?>
            <img src="offer-uploads/<?= $offerFirstFile ?>" alt="" class="img-item-size mb-2 img-thumbnail border border-secondary border-2">
          <?php else: ?>
            <video src="offer-uploads/<?= $offerFirstFile ?>" class="img-item-size mb-2 img-thumbnail border border-secondary border-2"></video>
          <?php endif ?>
          <p class="fw-bold h6"><?= $offerPartner['offer_title'] ?></p>
          <button value="<?= $offerPartner['offer_random_id'] ?>" class="forViewOfferModal btn btn-outline-secondary"
          data-bs-toggle="modal" data-bs-target="#offerModalView"
          >
            View Offer
          </button>
        </div>
      </div>