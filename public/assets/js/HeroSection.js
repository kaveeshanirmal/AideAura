document.addEventListener("DOMContentLoaded", function () {
  const slideContainer = document.getElementById("slide-2");
  const nextButton = document.getElementById("next");
  const prevButton = document.getElementById("prev");

  if (slideContainer && nextButton && prevButton) {
    nextButton.addEventListener("click", () => {
      const lists = document.querySelectorAll(".item-2");
      if (lists.length > 0) {
        slideContainer.appendChild(lists[0]);
      } else {
        console.warn("No items found in the slider.");
      }
    });

    prevButton.addEventListener("click", () => {
      const lists = document.querySelectorAll(".item-2");
      if (lists.length > 0) {
        slideContainer.prepend(lists[lists.length - 1]);
      } else {
        console.warn("No items found in the slider.");
      }
    });
  } else {
    console.error(
      "Required elements (slideContainer, nextButton, prevButton) not found in DOM.",
    );
  }

  // HeroThirdPage Slider (Swiper Implementation)
  const swiperContainer = document.querySelector(".mySwiper");
  if (swiperContainer) {
    new Swiper(".mySwiper", {
      slidesPerView: 1,
      grabCursor: true,
      loop: true,
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });
  }

  function handleHeroButton() {}
});
