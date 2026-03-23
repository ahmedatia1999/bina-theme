jQuery(function ($) {
  var $slider = $("#subscriptions-slider");

  if ($slider.length && typeof $.fn.owlCarousel === "function") {
    $slider.owlCarousel({
      items: 3,
      loop: true,
      margin: 20,
      nav: true,
      dots: true,
      responsive: {
        0: { items: 1 },
        768: { items: 2 },
        1200: { items: 3 }
      }
    });
  }
});
